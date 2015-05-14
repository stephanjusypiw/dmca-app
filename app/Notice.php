<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model {

    /**
     * fillable fields for new notice
     */
    protected $fillable = [
        'infringing_title',
        'infringing_link',
        'original_link',
        'original_description',
        'template',
        'content_removed',
        'provider_id',
    ];

    /**
     * A notice belongs to a recipient/provider
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        // must specify the primary key since function name is not
        // the same as primary key
        return $this->belongsTo('App\Provider', 'provider_id');
    }

    /**
     * A notice is created by a user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the email address for the recipient of the DMCA
     * notice
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->recipient->copyright_email;
    }

    /**
     * Get the email address of the notice
     * @return mixed
     */
    public function getOwnerEmail()
    {
        return $this->user->email;
    }



//
//    /**
//     * Open a new notice
//     * @param array $attributes
//     * @return static
//     */
//    public static function open(array $attributes)
//    {
//        // same as new Notice(array)
//        return new static($attributes); // new Notice(array)
//    }
//
//
//    /**
//     * Set he email template for the notice
//     * @param $template
//     * @return $this
//     */
//    public function useTemplate($template)
//    {
//        $this->template = $template;
//
//        return $this;
//    }

}
