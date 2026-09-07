<?php

namespace Contentify;
 
use Carbon\Carbon as OriginalCarbon;

/**
 * This class extends the original Carbon class. It adds localised date / time methods.
 */
class Carbon extends OriginalCarbon
{

    /**
     * Returns the date as a string in a format depending on the client
     *
     * @return string
     */
    public function date() : string
    {
        return $this->format(trans('app.date_format'));
    }

    /**
     * Returns the date in a format depending on the client and the time, both as a string
     *
     * @return string
     */
    public function dateTime() : string
    {
        return $this->format(trans('app.date_format')).' '.$this->format('H:i:s');
    }
}
