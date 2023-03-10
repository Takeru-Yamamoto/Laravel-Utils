<?php

namespace MyCustom\Utils\Date;

use MyCustom\Utils\BaseUtil;

use Carbon\Carbon;

final class DateUtil extends BaseUtil
{
    private Carbon $carbon;
    private ?string $date;

    function __construct(string|null $date)
    {
        parent::__construct(config("mycustoms.utils.logging_util_date", false), $this);
        $this->set($date);
    }
    function __clone()
    {
        $this->carbon = clone $this->carbon;
        $this->date   = clone $this->date;
    }

    public function params(): array
    {
        return [
            "carbon" => $this->carbon,
            "date"   => $this->date,
        ];
    }
    public function carbon(): Carbon
    {
        return $this->carbon;
    }
    public function isCarbon(): bool
    {
        return !is_null($this->date) && $this->date instanceof Carbon;
    }
    private function set(string|null $date): self
    {
        $this->date   = $date;
        $this->carbon = $this->isCarbon() ? $this->date : new Carbon($this->date);

        return $this;
    }
    public function reset(string|null $date = null): self
    {
        return $this->set($date);
    }


    /* format */
    public function format(string $format): string
    {
        return $this->carbon->format($format);
    }
    public function toDate(string $year = "Y", string $month = "m", string $day = "d", string $separator = "-"): string
    {
        return $this->format($year . $separator . $month . $separator . $day);
    }
    public function toTime(string $hour = "H", string $minute = "i", string $second = "s", string $separator = ":"): string
    {
        return $this->format($hour . $separator . $minute . $separator . $second);
    }
    public function toDatetime(
        string $year = "Y",
        string $month = "m",
        string $day = "d",
        string $dateSeparator = "-",
        string $hour = "H",
        string $minute = "i",
        string $second = "s",
        string $timeSeparator = ":",
    ): string {
        return $this->format($year . $dateSeparator . $month . $dateSeparator . $day . " " . $hour . $timeSeparator . $minute . $timeSeparator . $second);
    }


    /* to string */
    public function toDateString(): string
    {
        return $this->carbon->toDateString();
    }
    public function toDatetimeString(): string
    {
        return $this->carbon->toDateTimeString();
    }
    public function toTimeString(): string
    {
        return $this->carbon->toTimeString();
    }


    /* getter */
    public function year(): int
    {
        return $this->carbon->year;
    }
    public function month(): int
    {
        return $this->carbon->month;
    }
    public function day(): int
    {
        return $this->carbon->day;
    }
    public function hour(): int
    {
        return $this->carbon->hour;
    }
    public function minute(): int
    {
        return $this->carbon->minute;
    }
    public function second(): int
    {
        return $this->carbon->second;
    }
    public function dayOfYear(): int
    {
        return $this->carbon->dayOfYear;
    }
    public function weekOfYear(): int
    {
        return $this->carbon->weekOfYear;
    }
    public function daysInMonth(): int
    {
        return $this->carbon->daysInMonth;
    }
    public function weekNumberInMonth(): int
    {
        return $this->carbon->weekNumberInMonth;
    }
    public function yearsAgo(int $subYears): int
    {
        return $this->subYear($subYears)->year();
    }
    public function age(): int
    {
        return $this->carbon->age;
    }
    public function firstOfMonth(): string
    {
        return $this->carbon->firstOfMonth()->toDateTimeString();
    }
    public function endOfMonth(): string
    {
        return $this->carbon->endOfMonth()->toDateTimeString();
    }
    public function startOfWeek(): string
    {
        return $this->carbon->startOfWeek()->toDateTimeString();
    }
    public function endOfWeek(): string
    {
        return $this->carbon->endOfWeek()->toDateTimeString();
    }
    public function startOfDay(): string
    {
        return $this->carbon->startOfDay()->toDateTimeString();
    }
    public function endOfDay(): string
    {
        return $this->carbon->endOfDay()->toDateTimeString();
    }


    /* setter */
    public function setYear(int $year): self
    {
        $this->carbon->setDateTime($year, $this->month(), $this->day(), $this->hour(), $this->minute(), $this->second());
        return $this;
    }
    public function setMonth(int $month): self
    {
        $this->carbon->setDateTime($this->year(), $month, $this->day(), $this->hour(), $this->minute(), $this->second());
        return $this;
    }
    public function setDay(int $day): self
    {
        $this->carbon->setDateTime($this->year(), $this->month(), $day, $this->hour(), $this->minute(), $this->second());
        return $this;
    }
    public function setHour(int $hour): self
    {
        $this->carbon->setDateTime($this->year(), $this->month(), $this->day(), $hour, $this->minute(), $this->second());
        return $this;
    }
    public function setMinute(int $minute): self
    {
        $this->carbon->setDateTime($this->year(), $this->month(), $this->day(), $this->hour(), $minute, $this->second());
        return $this;
    }
    public function setSecond(int $second): self
    {
        $this->carbon->setDateTime($this->year(), $this->month(), $this->day(), $this->hour(), $this->minute(), $second);
        return $this;
    }


    /* calculation */
    public function addYear(int $year): self
    {
        $this->carbon->addYearsNoOverflow($year);
        return $this;
    }
    public function addYearWithOverflow(int $year): self
    {
        $this->carbon->addYearsWithOverflow($year);
        return $this;
    }
    public function subYear(int $year): self
    {
        $this->carbon->subYearsNoOverflow($year);
        return $this;
    }
    public function subYearWithOverflow(int $year): self
    {
        $this->carbon->subYearsWithOverflow($year);
        return $this;
    }
    public function diffYear(Carbon $carbon): int
    {
        return $this->carbon->diffInYears($carbon);
    }
    public function addMonth(int $month): self
    {
        $this->carbon->addMonthsNoOverflow($month);
        return $this;
    }
    public function addMonthWithOverflow(int $month): self
    {
        $this->carbon->addMonthsWithOverflow($month);
        return $this;
    }
    public function subMonth(int $month): self
    {
        $this->carbon->subMonthsNoOverflow($month);
        return $this;
    }
    public function subMonthWithOverflow(int $month): self
    {
        $this->carbon->subMonthsWithOverflow($month);
        return $this;
    }
    public function diffMonth(Carbon $carbon): int
    {
        return $this->carbon->diffInMonths($carbon);
    }
    public function addWeek(int $week): self
    {
        $this->carbon->addDays($week * 7);
        return $this;
    }
    public function subWeek(int $week): self
    {
        $this->carbon->subDays($week * 7);
        return $this;
    }
    public function diffWeek(Carbon $carbon): int
    {
        return $this->carbon->diffInWeeks($carbon);
    }
    public function addDay(int $day): self
    {
        $this->carbon->addDays($day);
        return $this;
    }
    public function subDay(int $day): self
    {
        $this->carbon->subDays($day);
        return $this;
    }
    public function diffDay(Carbon $carbon): int
    {
        return $this->carbon->diffInDays($carbon);
    }
    public function addHour(int $hour): self
    {
        $this->carbon->addHours($hour);
        return $this;
    }
    public function subHour(int $hour): self
    {
        $this->carbon->subHours($hour);
        return $this;
    }
    public function diffHour(Carbon $carbon): int
    {
        return $this->carbon->diffInHours($carbon);
    }
    public function addMinute(int $minute): self
    {
        $this->carbon->addMinutes($minute);
        return $this;
    }
    public function subMinute(int $minute): self
    {
        $this->carbon->subMinutes($minute);
        return $this;
    }
    public function diffMinute(Carbon $carbon): int
    {
        return $this->carbon->diffInMinutes($carbon);
    }
    public function addSecond(int $second): self
    {
        $this->carbon->addSeconds($second);
        return $this;
    }
    public function subSecond(int $second): self
    {
        $this->carbon->subSeconds($second);
        return $this;
    }
    public function diffSecond(Carbon $carbon): int
    {
        return $this->carbon->diffInSeconds($carbon);
    }


    /* check */
    public function isMatchFormat(string $format): bool
    {
        return $this->carbon->hasFormat($this->carbon, $format);
    }
    public function isMonday(): bool
    {
        return $this->carbon->isMonday();
    }
    public function isTuesday(): bool
    {
        return $this->carbon->isTuesday();
    }
    public function isWednesday(): bool
    {
        return $this->carbon->isWednesday();
    }
    public function isThursDay(): bool
    {
        return $this->carbon->isThursDay();
    }
    public function isFriday(): bool
    {
        return $this->carbon->isFriday();
    }
    public function isSaturday(): bool
    {
        return $this->carbon->isSaturday();
    }
    public function isSunday(): bool
    {
        return $this->carbon->isSunday();
    }
    public function isWeekday(): bool
    {
        return $this->carbon->isWeekday();
    }
    public function isWeekend(): bool
    {
        return $this->carbon->isWeekend();
    }
    public function isToday(): bool
    {
        return $this->carbon->isToday();
    }
    public function isLast(): bool
    {
        return $this->carbon->isLastOfMonth();
    }
    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }
    public function isPast(): bool
    {
        return $this->carbon->isPast();
    }
    public function isEqual(Carbon $carbon): bool
    {
        return $this->carbon->eq($carbon);
    }
    public function isGreater(Carbon $carbon): bool
    {
        return $this->carbon->gt($carbon);
    }
    public function isGreaterEqual(Carbon $carbon): bool
    {
        return $this->carbon->gte($carbon);
    }
    public function isLess(Carbon $carbon): bool
    {
        return $this->carbon->lt($carbon);
    }
    public function isLessEqual(Carbon $carbon): bool
    {
        return $this->carbon->lte($carbon);
    }
}
