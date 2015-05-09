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
     * Open a new notice
     * @param array $attributes
     * @return static
     */
    public static function open(array $attributes)
    {
        // same as new Notice(array)
        return new static($attributes); // new Notice(array)
    }


    /**
     * Set he email template for the notice
     * @param $template
     * @return $this
     */
    public function useTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

}
