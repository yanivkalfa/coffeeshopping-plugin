<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 10/9/2015
 * Time: 2:49 AM
 */

//
class currencyExchange {
    // Set our variables.
    public      $cacheFolder = null,
                $exchangeSourceURL = null,
                $exchangeRateTime = null,
                $cacheTime = null;                      //  Time to hold the rates 12 hours
    private     $exchangeRate = null;                    //  Array that holds exchange rates
    protected   $cacheFile = "currency-daily.xml";

    // Constructor loads up the the rates URL and makes the initial call to fetch the rates
    // Google uses: http://www.six-swiss-exchange.com/ajax/ihcc/currency-rates.json.
    /**
     * @func    __construct($url = "http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml")
     *  - init our class and set some base variables.
     * @param   string $url
     *  - European Central Bank         - http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml            - All rates are indexed by 1 EUR.   [ie. 4 ILS = 1 EUR]    ( use - Amount/FROM*TO)
     *  - Bank of Israel rates          - http://boi.org.il/currency.xml                                    - All rates are indexed by 1 ILS.   [ie. 3 ILS = 1 USD]    ( use - Amount/FROM*TO)
     *  - Google uses: (swiss-exchange) - http://www.six-swiss-exchange.com/ajax/ihcc/currency-rates.json   - All rates are index/currency.     [ie. 0.25 ILS = index] ( use - FROM/TO*Amount)
     */
    public function __construct($url = "http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml"){
        //  Setup the default values
        ini_set("allow_url_fopen", true);
        $this->exchangeSourceURL = $url;
        $this->cacheFolder = dirname(__FILE__);     //  Default to current folder - in production place $_CONFIG['cache_path'] here.
        $this->cacheTime = (60*60*12);              //  How long in seconds to hold the cached file (12HRs)
        $this->fetchExchangeRates();                //  Make the initial call to get the rates.
    }

    /**
     * @func exchangeRateConvert($from, $amount, $to = "ILS")
     *  - Converts any rate into another (rates are indexed by the EUR currency set by the European Central Bank)
     * @param   string      $from                       -   From which currency to convert. [3 digit currency symbol]
     * @param   integer     $amount                     -   How much?
     * @param   string      $to     [default: "ILS"]    -   To which currency to convert to.[3 digit currency symbol]
     * @return  float|int   $value                      -   Rounded to float with 2 decimals.
     */
    public function exchangeRateConvert($from, $amount, $to = "ILS"){
        if (isset($this->exchangeRate[$from]) && isset($this->exchangeRate[$to]) && !empty($this->exchangeRate[$from]) && !empty($this->exchangeRate[$to])){
            return round(($amount / $this->exchangeRate[$from]) * $this->exchangeRate[$to], 2);
        }else{
            return 0;
        }
    }

    /**
     * @func exchangeRate($fromCurrency, $toCurrency = "ILS")
     * @param   string  $fromCurrency                       -   From which currency to convert. [3 digit currency symbol]
     * @param   string  $toCurrency     [default: "ILS"]    -   To which currency to convert to.[3 digit currency symbol]
     * @return  float|int
     */
    public function exchangeRate($fromCurrency, $toCurrency = "ILS"){
        // Just exchange 1 and we'll have our rate.
        return $this->exchangeRateConvert($fromCurrency, 1, $toCurrency);
    }

    /**
     * @func fetchExchangeRates()
     *  - (Internal use only) - Sets our exchangeRate Array either from our local cache file, if local cache is too old - downloads from the European Central Bank.
     */
    public function fetchExchangeRates(){
        $now = time();                                          // Get current time.
        $cache = $this->cacheFolder . "/" . $this->cacheFile;   // Location of the cached file
        $this->exchangeRate['EUR'] = 1.00;                       // Add 'EUR' as 1, since our rates are indexed by it.
        $fileAge = 0;                                           // Holds the file age to compare to max cache time.

        // Check whether we have a recent copy of the data locally
        if (file_exists($cache)) {
            $fileAge = $now - filemtime($cache);
        }
        // Not in cache OR cache expired
        if (($fileAge > $this->cacheTime) || !file_exists($cache)){
            $fileRead = file_get_contents($this->exchangeSourceURL);
            $fh = fopen($cache, "w+");
            if (is_writable($cache)) {
                fwrite($fh, $fileRead);
            }else{
                die("File $cache is not WRITABLE - check folder permissions");
            }
        }else{
            // Use our file.
            $fileRead = file_get_contents($cache);
        }

        // Parse our file:
            // Get the time
        preg_match("/time='([[:graph:]]+)'/", $fileRead, $timeMatch);
        $this->exchangeRateTime = $timeMatch[1];
            // Get all currencies and rates.
        $regexp = "/currency=(?:'|\")([[:alpha:]]+)(?:'|\") rate=(?:'|\")([[:graph:]]+)(?:'|\")/i";
        preg_match_all($regexp, $fileRead, $matches, PREG_SET_ORDER);

        foreach ($matches as $currency) {
            $this->exchangeRate[$currency[1]] = $currency[2];
        }
    }
}

?>