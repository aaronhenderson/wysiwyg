<?php
namespace AaronHenderson;

class WordGame {

    /**
     * Size of scoreboard
     */
    const SCORE_BOARD_SIZE = 10;


    /**
     * File containing acceptable words to be submitted
     */
    const WORD_LIST_FILE = 'storage/wordlists/english_alpha.txt';


    /**
     * Array of acceptable words
     *
     * @var array
     */
    private $word_list;


    /**
     * Pool of letters to use for word submissions
     *
     * @var array
     */
    private $letter_pool;


    /**
     * The base string to form our pool of letters
     *
     * @var string
     */
    private $base_string;


    /**
     * List of high scores
     *
     * @var array
     */
    public $scores = array();


    /**
     * WordGame constructor.
     *
     * Extract letters from base string to form a letter pool and load the word list so a game can be played
     *
     * @param $base_string
     */
    public function __construct($base_string)
    {
        $this->base_string = $base_string;

        $this->letter_pool = str_split($base_string);

        $this->word_list = file(self::WORD_LIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }


    /**
     * return the letter pool as a string
     *
     * @return string
     */
    public function getBaseString()
    {
        return $this->base_string;
    }


    /**
     * Submit a word for scoring
     *
     * A word is accepted if it's letters are in the base string / letter pool
     * If accepted and the score high enough the word should be added to the high score list
     *
     * @param $word
     * @return integer
     */
    public function submitWord($word)
    {
        $word = strtolower($word);

        $score = $this->scoreWord($word);

        if($score > 0 && $this->getPositionWithWordEntry($word) === null) {
            // word is not already in high scores
            $this->scores[] = array(
                'word' => $word,
                'score' => $score,
                'time' => microtime(true)
            );

            // sort the scores by score and time
            usort($this->scores, function ($a, $b){
                if($a['score'] > $b['score'] || ($a['score'] == $b['score'] && $a['time'] < $b['time']) ) {
                    return -1;
                }
                return 1;
            });

            // prevent scoreboard getting too big
            if(count($this->scores) > self::SCORE_BOARD_SIZE) {
                unset($this->scores[self::SCORE_BOARD_SIZE]);
            }
        }

        return $score;
    }


    /**
     * A word gets a score if it is in the word list and it can be formed out of the letter pool
     *
     * @param $word
     * @return int
     */
    private function scoreWord($word)
    {
        return $this->isWordAllowed($word) ? strlen($word) : 0;
    }


    /**
     * Return true if word is allowed otherwise false
     *
     * A word is allowed if it can be made from the base string and exists in the word list
     *
     * @param string $word
     * @return bool
     */
    private function isWordAllowed($word)
    {
        if($this->isWordInList($word) === false) {
            return false;
        }

        return $this->wordCanBeMadeFromPool($word);
    }


    /**
     * Returns true if word can be made from the letter pool, otherwise false
     *
     * @param  string $word
     * @return bool
     */
    private function wordCanBeMadeFromPool($word)
    {
        $letter_pool = $this->letter_pool;
        foreach (str_split($word) as $letter){
            $pool_key = array_search($letter, $letter_pool);
            if($pool_key === false) {
                return false;
            }
            unset($letter_pool[$pool_key]);
        }

        return true;
    }


    /**
     * Return true / false if word exists in word list
     *
     * @param $word
     * @return bool
     */
    private function isWordInList($word)
    {
        return in_array($word, $this->word_list);
    }


    /**
     * Return position of a word entry in the high scores, otherwise return null
     *
     * @param $word
     * @return int|null
     */
    private function getPositionWithWordEntry($word)
    {
        $position = array_search($word, array_column($this->scores, 'word'));
        return is_numeric($position) ? $position : null;
    }


    /**
     * Return score at position
     * Position 0 being the best score and 9 the lowest
     *
     * @param $position
     * @return integer|null
     */
    public function getScoreAtPosition($position)
    {
        return isset($this->scores[$position]) ? $this->scores[$position]['score'] : null;
    }


    /**
     * Return word at position otherwise null
     * Position 0 being the best score and 0 the lowest
     *
     * @param $position
     * @return string|null
     */
    public function getWordEntryAtPosition($position)
    {
        return isset($this->scores[$position]) ? $this->scores[$position]['word'] : null;
    }

    /**
     * Try all allowed words with a game
     */
    public function tryAllWords()
    {
        foreach ($this->word_list as $word) {
            $this->submitWord($word);
        }
    }

    /**
     * Generate a base string from words in the word list
     *
     * @param $min_length
     * @return string
     */
    public static function generateBaseString($min_length)
    {
        $word_list = file(self::WORD_LIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $base_string = '';
        while (strlen($base_string) < $min_length) {
            $base_string .= $word_list[rand(0, count($word_list) - 1)];
        }

        return str_shuffle($base_string);

    }
}