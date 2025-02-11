<?php
namespace AaronHenderson\ContactRequest;

use AaronHenderson\Template;
use AaronHenderson\WordGame;

class PageWithWordGame extends Page
{

    /**
     * The template for our word game
     */
    const WORDGAME_TEMPLATE = 'wordgame.html';


    /**
     * Wordgame instance
     *
     * @var WordGame|null
     */
    private $word_game;


    /**
     * PageWithWordGame constructor.
     *
     * @param array $context
     * @throws \Exception
     */
    public function __construct(array $context = array())
    {
        // Replace footer section with word game menu html
        if ($this->showGame()) {
            // load a game / start a new one
            $this->word_game = $this->loadGame($this->baseString());

            if (isset($_GET['autosolve'])) {
                set_time_limit(600); // increase time limit due to expensive process
                $this->word_game->tryAllWords();
            }

            if (isset($_POST['inputWord'])) {
                $score = $this->word_game->submitWord(strip_tags(trim($_POST['inputWord'])));
                if ($score > 0) {
                    $context['game_alert'] = $this->alert('Word scored: ' . $score, 'success');
                } else {
                    $context['game_alert'] = $this->alert('Word rejected');
                }
                $this->saveGame($this->word_game);
            }

            if($this->showGame() === true) {
                $context['footer_section'] = $this->gameHtml($context);
            }
        }

        if (isset($_GET['wordgame']) && $_GET['wordgame'] === 'no') {
            $context['form_alert'] = $this->alert('That\'s okay, not everyone likes games', 'info');
        }


        parent::__construct($context);
    }


    /**
     * Return game HTML
     *
     * @param array $extra_context
     * @return string
     * @throws Exception
     */
    public function gameHtml(array $extra_context = array())
    {
        $formatted_string = '<span class="base-string--letter">' . implode(
            '</span><span class="base-string--letter">',
            str_split($this->word_game->getBaseString())
        ) . '</span>';

        $context = array_merge(
            array(
                'base_string' => $this->word_game->getBaseString(),

                'formatted_base_string' => $formatted_string,

                'high_score_table' => $this->highScoreTableHtml(),

                'game_alert' => '',
            ),
            $extra_context
        );

        return Template::fetch(self::WORDGAME_TEMPLATE, $context);
    }


    /**
     * Return high score table html using wordgame scores
     *
     * @return string
     */
    private function highScoreTableHtml()
    {
        $table_rows_html = '';

        for ($i = 0; $i < WordGame::SCORE_BOARD_SIZE; $i++) {
            $table_rows_html .= Template::fetch('wordgame/highscore-table-row.html', array(
                'i' => $i+1,
                'word_entry' => $this->word_game->getWordEntryAtPosition($i),
                'word_score' =>  $this->word_game->getScoreAtPosition($i)
            ));
        }

        $html = Template::fetch('wordgame/highscore-table.html', array(
            'table_size' => WordGame::SCORE_BOARD_SIZE,
            'table_rows' => $table_rows_html
        ));

        return $html;
    }


    /**
     * Try resolve the base string as a GET parameter otherwise generate a base string from the word list
     *
     * @return string
     */
    private function baseString()
    {
        if (isset($_GET['base_string']) && ctype_alpha($_GET['base_string'])) {
            return $_GET['base_string'];
        }

        return WordGame::generateBaseString(12);
    }


    /**
     * Determine if we should display the word game or not
     *
     * @return bool
     */
    private function showGame()
    {
        return isset($_GET['wordgame']) && $_GET['wordgame'] === 'yes';
    }

    /**
     * Save a wordgame
     *
     * @param WordGame $word_game
     * @return bool|int
     */
    private function saveGame(WordGame $word_game)
    {
        $save_file = 'storage/wordgames/' . md5($word_game->getBaseString()) . '.save';

        return file_put_contents($save_file, serialize($word_game));
    }


    /**
     * Load a saved WordGame or create a new one
     *
     * @param string $base_string
     * @return false|WordGame
     */
    private function loadGame($base_string)
    {

        $save_file = 'storage/wordgames/' . md5($base_string) . '.save';

        if (file_exists($save_file)) {
            $word_game = unserialize(file_get_contents($save_file, true));
            if ($word_game instanceof WordGame) {
                return $word_game;
            }
        }

        return new WordGame($base_string);
    }
}