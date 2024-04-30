<?php

namespace Domain\Date;

use DateTime;

class DateHandler
{
    private DateTime $dateTime;
    private string $timezone;

    public function __construct(string $date, string $timezone)
    {
        $this->timezone = $timezone;
        $this->dateTime = new DateTime("{$date} 12:00:00", new \DateTimeZone($timezone));
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function getMinutesOffsetFromUtc(): string {
        $offset = $this->dateTime->getOffset() / 60;
        return ($offset > 0 ? "+" :  "") . $offset;
    }

    public function getFebruaryDays(): int {
        return $this->getMonthDays(2);
    }

    public function getCurrentMonthDays(): int {
        return $this->getMonthDays((int) $this->dateTime->format('m'));
    }
    public function getCurrentMonthName(): string {
        return $this->dateTime->format('F');
    }

    private function getMonthDays(int $month) {
         /**
         * If we'd use ext-calendar, we could just do this:
         * return cal_days_in_month(CAL_GREGORIAN, $month, $this->dateTime->format('Y'));
         */
        $newDate = new DateTime("{$this->dateTime->format('Y')}-{$month}-01 00:00:00");
        return (int) $newDate->format('t');
    }

}