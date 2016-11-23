<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\LoggedInRequest;
use App\Http\Requests\AdminRequest;

use App\Models\Member;
use App\Models\Project;

class ProjectController extends BaseController {
	public function __construct() {
		$this->middleware('auth');
	}
	
	/////////////////////////////// Viewing Projects ///////////////////////////////
	
	public function getIndex(Request $request) {
		if (!Auth::check()) {
			return redirect()->action('HomeController@getIndex')->with('msg', 'Permission Denied');
		}
		
		$projects = Auth::user()->projects;
		
		return view('pages.projects',compact("projects"));
	}
	
	public function getAll(AdminRequest $request) {
		$projects = Project::all();
		$allProjects = true;
		
		return view('pages.projects',compact("projects","allProjects"));
	}
	
	public function getProject(LoggedInRequest $request, $projectID) {
		$project = Project::findOrFail($projectID);
		
		if ($this->canAccessProject($request, $project) == false) {
			$request->session()->flash('msg', 'Error: Project Not Found.');
			return $this->getProjects($request);
		}
		
		$members = $project->members;
		
		return view('pages.project', compact("project","members"));
	}
	
	public function canAccessProject($request, $project) {
		$member = Auth::user();
		
		return $project->members->contains($member) || Auth::user()->admin;
	}
	
	/////////////////////////////// Creating Projects ///////////////////////////////
	
	public function getCreate(LoggedInRequest $request) {
		$project = new Project;
		$project->id = 0;
		$members = [];
		
		return view('pages.project', compact("project","members"));
	}
	
	/////////////////////////////// Editing Projects ///////////////////////////////
	
	public function postProject(LoggedInRequest $request, $projectID) {
		$projectName = $request->input("name");
		$projectDescription = $request->input("description");
		
		if($projectID == 0) { // Create New Project
			$project = new Project;
		} else {
			$project = Project::find($projectID);
			if ($this->canAccessProject($request, $project) == false) { // Verify Permissions
				$request->session()->flash('msg', 'Error: Project Not Found.');
				return $this->getProjects($request);
			}
		}
		
		// Verify Input
		if(is_null($project)) {
			$request->session()->flash('msg', 'Error: Project Not Found.');
			return $this->getProjects($request);
		}
		
		// Edit Project
		$project->name = $projectName;
		$project->description = $projectDescription;
		$project->save();
		
		// Return Response
		if($projectID == 0) { // New Project
			$member = Auth::user();
			$project->members()->attach($member->id); // Attach Project to Member
			return redirect()->action('ProjectController@getProject', [$project->id])->with('msg', 'Project Created!');
		} else {
			$request->session()->flash('msg', 'Project Updated!');
			return $this->getProject($request, $projectID);
		}
	}
	
	public function getDelete(LoggedInRequest $request, $projectID) {
		$project = Project::findOrFail($projectID);
		
		if ($this->canAccessProject($request, $project) == false) {
			$request->session()->flash('msg', 'Error: Project Not Found.');
			return $this->getProjects($request);
		}
		
		$project->delete();
		
		return redirect()->action('ProjectController@getIndex')->with('msg', 'Success: Project Deleted. If this was by mistake, contact an organizer to reverse the change.');
	}
	
	/////////////////////////////// Editing Project Members ///////////////////////////////
	
	public function postMemberAdd(LoggedInRequest $request, $projectID) {
		$project = Project::findOrFail($projectID);
		$memberInput = $request->input("member");
		$member = Member::where('name',$memberInput)->orWhere('email',$memberInput)->first();
		
		if ($this->canAccessProject($request, $project) == false) {
			$request->session()->flash('msg', 'Error: Project Not Found');
			return $this->getProjects($request);
		}
		
		if ($member == null) {
			$request->session()->flash('msg', 'Error: Member not found. Do they have a '.env("ORG_NAME").' account?');
			return $this->getProject($request, $projectID);
		}
		
		if ($project->members()->find($member->id)) {
			$request->session()->flash('msg', 'Error: Member already in team');
			return $this->getProject($request, $projectID);
		}
		
		$project->members()->attach($member->id);
		
		return redirect()->action('ProjectController@getProject', [$projectID])->with('msg', 'Success: Added '.$member->name.' to project '.$project->name);
	}
	
	public function getMemberRemove(LoggedInRequest $request, $projectID, $memberID) {
		$project = Project::findOrFail($projectID);
		$member = Member::findOrFail($memberID);
		
		if ($this->canAccessProject($request, $project) == false) {
			$request->session()->flash('msg', 'Error: Project Not Found');
			return $this->getProjects($request);
		}
		
		if ($member == null) {
			$request->session()->flash('msg', 'Error: Member Not Found');
			return $this->getProject($request, $projectID);
		}
		
		if (count($project->members) <= 1) {
			$request->session()->flash('msg', 'Error: Cannot leave project with only 1 member. Please delete project to remove.');
			return $this->getProject($request, $projectID);
		}
		
		if ($project->members()->find($member->id) == false) {
			$request->session()->flash('msg', 'Error: Member is not in team');
			return $this->getProject($request, $projectID);
		}
		
		$project->members()->detach($member->id);
		
		return redirect()->action('ProjectController@getProject', [$projectID])->with('msg', 'Success: Removed '.$member->name.' from project '.$project->name);
	}
    
}