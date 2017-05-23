<?php
/**
 * User: Ashwin Gokhale
 * Date: 5/17/17
 * Time: 10:11 PM
 */

namespace App\Console\Commands;

use App\Http\Controllers\EventController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\EditEventRequest;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FacebookEvents extends Command{
    public $fb_id = 'purduehackers';
    public $fb;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls events from Facebook and updates the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->fb = new \Facebook\Facebook([
            'app_id' => '1678216509149998',
            'app_secret' => '5b1c412c247a83ad127b97962fcd36c6',
            'default_graph_version' => 'v2.9',
            'default_access_token' => '1678216509149998|n989h4-eAYuaN80n0us6NjziUP0'
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        // Get the 25 most recent events
        $events = $this->getEvent(['limit' => 25]);
        foreach ($events as $event){
            // Parse event data from Facebook
            $data = array (
                'eventName' => $event['name'],
                'facebook'  => "www.facebook.com/events/".$event['id']
            );

            if (array_key_exists('start_time', $event)){
                $data['date'] = date_format($event['start_time'], 'Y-m-d');
                $data['hour'] = date_format($event['start_time'], 'H');
                $data['minute'] = date_format($event['start_time'], 'i');
            }
            else {
                $data['date'] = "";
                $data['hour'] = "";
                $data['minute'] = "";
            }

            if (array_key_exists('place', $event))
                $data['location'] = $event['place']['name'];
            else
                $data['location'] = "";

            $event = new Event();

            // Create Purdue Hackers event from Facebook event data
            $event->name = $data['eventName'];
            $event->privateEvent = false;
            $event->requiresApplication = false;
            $event->event_time = new Carbon($data['date'].' '.$data['hour'].':'.$data['minute']);
            $event->location = $data['location'];
            $event->facebook = $data['facebook'];
            $event->fromFacebook = true;

            // Get non-private, Facebook events that have the Facebook event name
            $events = Event::where('privateEvent', 'like', '%0%')->
                             where('fromFacebook', 'like', '1')->
                             where('name', 'like', '%'.$event->name.'%')->
                             get();

            // If there is an event, update the event data
            if (count($events) != 0){
                foreach ($events as $e) {
                    $e->requiresApplication = $event->requiresApplication;
                    $e->event_time = $event->event_time;
                    $e->location = $event->location;
                    $e->facebook = $event->facebook;
                    $this->comment("Updating event: $event->name");
                    $e->update();
                }
            }

            // Otherwise, save the event data in the database
            else {
                $this->comment("Creating Event: $event->name");
                $event->save();
            }
        }
    }

    public function getEvent($args = null){

        // Default is most recent event
        $payload = '?limit=1';

        if ($args){
            $payload = '?';
            foreach ($args as $key => $value){
                $payload .= $key . '=' . $value;
            }
        }

        $response = $this->fb->get('/' . $this->fb_id . '/events/attending' . $payload);

        return $response->getGraphEdge()->asArray();
    }
}