<?php

namespace App;

class DateHelper
{

  private static function getNowDate()
  {
    return date('Y-m-d');
  }

  private static function getYearFromDate($date)
  {
    return substr($date, -10, 4);
  }

  private static function getMonthFromDate($date)
  {
    return substr($date, -5, 2);
  }

  private static function getPreviousMonth($date)
  {
    if (self::getMonthFromDate($date) == "01")
    {
        return "12";
    }
    else
    {
    $number_of_current_month = intval(self::getMonthFromDate($date));
    if ($number_of_current_month > 10)
    {
        return strval($number_of_current_month - 1);
    }
    else
    {
        return "0".strval($number_of_current_month - 1);
    }
    }
  }

  private static function getYearOfPreviousMonth($date, $previous_month)
  {
    if ($previous_month == "12") 
    {
    $year = strval(intval(self::getYearFromDate($date)) - 1);
    }
    else
    {
    $year = self::getYearFromDate($date);
    }
    return $year;
  }

  public static function setStartDate($time_period)
  {
    $date = self::getNowDate();
    $year = self::getYearFromDate($date);
    $current_month = self::getMonthFromDate($date);
    $previous_month = self::getPreviousMonth($date);
    $year_of_previous_month = self::getYearOfPreviousMonth($date, $previous_month);
    $start_date = "";

    switch($time_period) {

      case "current_month":
          $start_date = $year."-".$current_month."-01";
          break;
      case "previous_month": 
          $start_date = $year_of_previous_month."-".$previous_month."-01";
          break;
      case "current_year":
          $start_date = $year."-01-01";
          break;
      case "total_time";
          $start_date = "2000-01-01";
          break;
    }

    return $start_date;
  }

  public static function setEndDate($time_period)
  {
    $date = self::getNowDate();
    $previous_month = self::getPreviousMonth($date);
    $year_of_previous_month = self::getYearOfPreviousMonth($date, $previous_month);

    if($time_period == "previous_month") {

      $number_of_previous_month = intval($previous_month);
      $number_of_days_in_previous_month = cal_days_in_month(CAL_GREGORIAN, $number_of_previous_month, $year_of_previous_month);

      if($number_of_days_in_previous_month > 9) {
        $end_date = $year_of_previous_month."-".$previous_month."-".strval($number_of_days_in_previous_month);
        } else {
            $end_date = $year_of_previous_month."-".$previous_month."-0".strval($number_of_days_in_previous_month);
        }
    } else {

    $end_date = $date;

    }

    return $end_date;
  }
}
