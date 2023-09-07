<?php

namespace App\Console\Commands;
use App\Mail\Sendmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:adminInactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Admin And his all user deactive command';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users =  User::whereaccount_expire_date(date('Y-m-d'))->wherestatus(1)->get(['id', 'admin_id', 'name', 'phone', 'email', 'account_expire_date', 'status']);
        foreach ($users as $user) {
            User::whereIn('admin_id', [$user->id])->wherestatus(1)->update(['status' => 0]);

            $data = array(
                'subject' => 'Account Deactive',
                'name' => $user->name,
                'email' => $user->email,
                'message' => 'Your Account was Deactive for Insaficent Balance.If You Want Active Contact  SOHIBD  Support Team',
            );
            User::whereid($user->id)->update(['status' => 0]);
            Mail::to($user->email)->send(new SendMail($data));
        }
        Log::info($users);
        $this->info('User Deactive Command  Done');
    }
}
