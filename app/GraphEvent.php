<?php

namespace App;

class GraphEvent
{
    public $reminderMinutesBeforeStart;
    public $isReminderOn;
    public $subject;
    public $isOrganizer;
    public $responseRequested;
    public $type;
    public $body;
    public $start;
    public $end;
    public $location;
    public $recurrence;
    public $attendees;
    public $organizer;

    /**
     * GraphEvent constructor.
     * @param $reminderMinutesBeforeStart
     * @param $isReminderOn
     * @param $subject
     * @param $isOrganizer
     * @param $responseRequested
     * @param $type
     * @param $bodyContent
     * @param $startDateTime
     * @param $endDateTime
     * @param $locationDisplayName
     * @param $attendeesEmailAddressArray
     * @param $organizerName
     * @param $organizerEmailAddress
     */
    public function __construct(
        $reminderMinutesBeforeStart, //int
        $isReminderOn, //boolean
        $subject, //string
        $isOrganizer,
        $responseRequested,
        $type,
        $bodyContent,
        $startDateTime,
        $endDateTime,
        $locationDisplayName,
        $attendeesEmailAddressArray,
        $organizerName,
        $organizerEmailAddress)
    {
        $this->reminderMinutesBeforeStart = $reminderMinutesBeforeStart;
        $this->isReminderOn = $isReminderOn;
        $this->subject = $subject;
        $this->isOrganizer = $isOrganizer;
        $this->responseRequested = $responseRequested;
        $this->type = $type;
        $this->body = [
            'contentType' => 'html',
            'content' => $bodyContent,
        ];
        $this->start = [
            'dateTime' =>  $startDateTime,
            'timeZone' =>  'Asia/Singapore'
        ];
        $this->end = [
            'dateTime' =>  $endDateTime,
            'timeZone' =>  'Asia/Singapore'
        ];
        $this->recurrence = null;

        $attendeesArray = [];
        foreach($attendeesEmailAddressArray as $attendeesEmailAddress){
            $attendeesArray[] = [
                'type' => 'required',
                'emailAddress' => $attendeesEmailAddress,
            ];

        }
        $this->organizer = [
            'emailAddress' => [
                'name' => $organizerName,
                'address' => $organizerEmailAddress
            ]
        ];
        $this->attendees = array_values($attendeesArray);
        $this->location = [
            'displayName' => $locationDisplayName
        ];
    }
}
