<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\PrepareNoticeRequest;
use App\Notice;
use App\Provider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class NoticesController extends Controller {


    /**
     * Create a new notices controller instance
     */
    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
    }

    /**
     * Show all notices
     */
    public function index()
    {
        $notices = $this->user->notices;

        return view('notices.index', compact('notices'));
    }

    /**
     * Show a page to create a new notice
     * @return \Response
     */
    public function create()
    {
        // get list of providers
        $providers = Provider::lists('name', 'id');

        return view('notices.create', compact('providers'));
    }


    /**
     * Ask the user to conform the DMCA that will be delivered
     * @param PrepareNoticeRequest $request

     * @return \Illuminate\View\View
     */
    public function confirm(PrepareNoticeRequest $request)
    {

        $template = $this->compileDmcaTemplate($data = $request->all());

        session()->flash('dmca', $data);

        return view('notices.confirm', compact('template'));

    }

    /**
     *
     * Store a new DMCA notice
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $notice = $this->createNotice($request);

        // fire off the email
        Mail::queue('emails.dmca', compact('notice'), function($message) use ($notice){
            $message->from($notice->getOwnerEmail())
                    ->to($notice->getRecipientEmail())
                    ->subject('DMCA Notice');
        });

        return redirect('notices');
    }

    /** use
     * Compile the DMCA template from the form data
     * @param $data
     *
     * @return mixed
     */
    private function compileDmcaTemplate($data)
    {
        $data = $data + [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ];

        return view()->file(app_path('Http/Templates/dmca.blade.php'), $data);

    }

    /**
     * Create and persist new DMCA notice
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function createNotice(Request $request)
    {
        $notice = session()->get('dmca') + ['template' => $request->input('template')];
      //  $notice = Notice::open($data)->useTemplate($request->input('template'));
        $notice = $this->user->notices()->create($notice);

        return $notice;
    }
}
