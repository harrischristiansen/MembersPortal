<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\AdminPermissionRequest;
use App\Models\Permission;
use App\Models\Member;

class PermissionController extends BaseController {
	
	/////////////////////////////// Permissions List ///////////////////////////////
	
	public function getIndex(Request $request) {
		if (Gate::denies('permission', 'permissions')) {
			return redirect()->guest('login')->with('msg', 'Permission Denied');
		}
		
		$permissions = Permission::all();
		return view('pages.permissions',compact("permissions"));
	}
	
	/////////////////////////////// Create Permission ///////////////////////////////
	
	public function postIndex(AdminPermissionRequest $request) {
		$permission_name = $request->input("permission_name");
		$description = $request->input("description");
		
		if (strlen($permission_name) < 3) {
			$request->session()->flash('msg', 'Error: A name is required.');
			return $this->getIndex($request);
		}
		
		$permission = new Permission;
		$permission->name = $permission_name;
		$permission->description = $description;
		$permission->save();
		
		return $this->getIndex($request);
	}
	
	/////////////////////////////// View Permission ///////////////////////////////
	
	public function getPermission(PermissionRequest $request, $permissionID) {
		$permission = Permission::findOrFail($permissionID);
		
		$members = $permission->members;
		
		foreach ($members as $member) { // Pre-calculate recorded_by
			$recorded_member = Member::find($member->permissions()->find($permissionID)->pivot->recorded_by);
			$member->recorded_by = $recorded_member;
			$member->authorized_at = $member->permissions()->find($permissionID)->pivot->created_at;
		}
		
		return view('pages.permission', compact("permission","members"));
	}
	
	/////////////////////////////// Add Member ///////////////////////////////
	
	public function postAdd(PermissionRequest $request, $permissionID) {
		$permission = Permission::findOrFail($permissionID);
		$member_name = $request->input('member_name');
		$member = Member::where('name',$member_name)->orWhere('email',$member_name)->firstOrFail();
		
		$permission->members()->attach($member->id,['recorded_by' => Auth::user()->id]); // Save Record
		
		$request->session()->flash('msg', 'Success: Added '.$member->name);
		return $this->getPermission($request, $permissionID);
	}
	
	/////////////////////////////// Delete Member ///////////////////////////////
	
	public function getDeleteMember(PermissionRequest $request, $permissionID, $memberID) {
		$member = Member::findOrFail($memberID);
		$permission = Permission::findOrFail($permissionID);
		$permission->members()->detach($memberID);
		
		$request->session()->flash('msg', 'Success: Removed '.$member->name);
		return $this->getPermission($request, $permissionID);
	}
	
	/////////////////////////////// Delete Permission ///////////////////////////////
	
	public function getDelete(AdminPermissionRequest $request, $permissionID) {
		$permission = Permission::findOrFail($permissionID);
		$permission->delete();
		
		return redirect()->action('PermissionController@getIndex')->with('msg', 'Success: Deleted permission '.$permission->name.'.');
	}
    
}