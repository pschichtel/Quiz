<?php
    class Quiz implements Serializable, Countable, IteratorAggregate, ArrayAccess
    {
        protected $config;
        protected $questions;
        protected $currentQuestion;
        protected $points;
        protected $fails;
    
        public function __construct($quizfile)
        {
            $xmltree = null;
            if (file_exists($quizfile))
            {
                $xmlpath =& $quizfile;
            }
            elseif (file_exists(iQUIZ_ROOT . DS . 'quize' . DS . $quizfile . '.xml'))
            {
                $xmlpath = iQUIZ_ROOT . DS . 'quize' . DS . $quizfile . '.xml';
            }
            else
            {
                throw new Exception('The XML file was not found! - "' . iQUIZ_ROOT . DS . 'quize' . DS . $quizfile . '.xml' . '"');
            }
            
            $xmltree = new SimpleXMLElement(file_get_contents($xmlpath));
            
            if (!isset($xmltree->config->entry, $xmltree->questions->question))
            {
                throw new Exception('Invalid XML file given!');
            }

            foreach ($xmltree->config->entry as $entry)
            {
                $attribs = $entry->attributes();
                if (!isset($attribs['name']))
                {
                    continue;
                }
                $this->config[strval($attribs['name'])] = strval($entry);
            }

            foreach ($xmltree->questions->question as $question)
            {
                if (!isset($question->text, $question->answers->answer))
                {
                    continue;
                }
                $text = strval($question->text);
                $correctIndex = 0;
                $answers = array();
                $i = 0;
                foreach ($question->answers->answer as $answer)
                {
                    $attribs = $answer->attributes();
                    $answers[] = new QuizAnswer(strval($answer), isset($attribs['correct']));
                    if (isset($attribs['correct']))
                    {
                        $correctIndex = $i;
                    }
                    $i++;
                }

                $rightSound = '';
                if (isset($question->rightSound))
                {
                    $rightSound = strval($question->rightSound);
                }
                elseif ($this->configEntryExists('default.right_sound'))
                {
                    $rightSound = $this->getConfigEntry('default.right_sound');
                }
                else
                {
                    continue;
                }

                $rightText = '';
                if (isset($question->right))
                {
                    $rightText = strval($question->right);
                }
                elseif ($this->configEntryExists('default.right'))
                {
                    $rightText = $this->getConfigEntry('default.right');
                }
                else
                {
                    continue;
                }

                $wrongText = '';
                if (isset($question->wrong))
                {
                    $wrongText = strval($question->wrong);
                }
                elseif ($this->configEntryExists('default.wrong'))
                {
                    $wrongText = $this->getConfigEntry('default.wrong');
                }
                else
                {
                    continue;
                }

                $wrongSound = '';
                if (isset($question->wrongSound))
                {
                    $wrongSound = strval($question->wrongSound);
                }
                elseif ($this->configEntryExists('default.wrong_sound'))
                {
                    $wrongSound = $this->getConfigEntry('default.wrong_sound');
                }
                else
                {
                    continue;
                }

                $this->questions[] = new QuizQuestion($text, $answers, $correctIndex, $rightText, $rightSound, $wrongText, $wrongSound);

                $this->currentQuestion = 0;
                $this->points = 0;
            }
        }

        public function increasePoints()
        {
            $points = 1;
            if ($this->configEntryExists('right_points'))
            {
                $points = intval($this->getConfigEntry('right_points'));
            }
            $this->points += $points;
        }

        public function decreasePoints()
        {
            $points = 1;
            if ($this->configEntryExists('wrong_points'))
            {
                $points = intval($this->getConfigEntry('wrong_points'));
            }
            $this->points -= $points;
        }
        
        public function configEntryExists($name)
        {
            return isset($this->config[$name]);
        }

        public function getConfigEntry($name)
        {
            if ($this->configEntryExists($name))
            {
                return $this->config[$name];
            }
            else
            {
                return null;
            }
        }

        public function getWelcomeMessage()
        {
            if ($this->configEntryExists('welcome'))
            {
                $vars = array(
                    '{QUESTIONS}' => count($this->questions)
                );
                return str_replace(array_keys($vars), array_values($vars), $this->getConfigEntry('welcome'));
            }
            else
            {
                return 'Es wurde keine Willkommensnachricht gefunden!';
            }
        }

        public function getCompletedMessage()
        {
            $count = count($this);
            $wrong = $this->fails;
            $right = $count - $wrong;
            if ($this->configEntryExists('completed'))
            {
                $vars = array(
                    '{POINTS}' => strval($this->points),
                    '{RIGHT}' =>  strval($right),
                    '{RIGHT%}' => number_format($right / $count * 100, 2),
                    '{WRONG}' => strval($wrong),
                    '{WRONG%}' => number_format($wrong / $count * 100, 2)
                );
                return str_replace(array_keys($vars), array_values($vars), $this->getConfigEntry('completed'));
            }
            else
            {
                return 'Es wurde keine Nachricht für das abschließen des Quiz gefunden.';
            }
        }

        public function failed()
        {
            $this->fails++;
        }

        public function getBackground()
        {
            if (file_exists(iQUIZ_ROOT . DS . 'gfx' . DS . 'backgrounds' . DS . $this->getConfigEntry('name') . '.png'))
            {
                return dirname($_SERVER['SCRIPT_NAME']) . '/gfx/backgrounds/' . $this->getConfigEntry('name') . '.png';
            }
            else
            {
                return false;
            }
        }


        /*
         * Interface method
         */
        public function getIterator()
        {
            return new ArrayIterator($this->questions);
        }

        public function count()
        {
            return count($this->questions);
        }

        public function current()
        {
            return $this->questions[$this->currentQuestion];
        }

        public function key()
        {
            return $this->currentQuestion;
        }

        public function next()
        {
            if ($this->valid())
            {
                return ++$this->currentQuestion;
            }
        }

        public function last()
        {
            if ($this->valid())
            {
                return --$this->currentQuestion;
            }
        }

        public function valid()
        {
            return isset($this->questions[$this->currentQuestion]);
        }

        public function offsetExists($offset)
        {
            return isset($this->questions[$offset]);
        }

        public function offsetGet($offset)
        {
            return $this->questions[$offset];
        }

        public function offsetSet($offset, $value)
        {}

        public function offsetUnset($offset)
        {}

        public function serialize()
        {
            return serialize(array($this->config, $this->questions, $this->currentQuestion, $this->points, $this->fails));
        }

        public function unserialize($serialized)
        {
            $data = unserialize($serialized);
            $this->config = $data[0];
            $this->questions = $data[1];
            $this->currentQuestion = $data[2];
            $this->points = $data[3];
            $this->fails = $data[4];
        }

        public static function getQuizes()
        {
            $quizes = array();
            foreach (glob(iQUIZ_ROOT . DS . 'quize' . DS . '*.xml') as $quiz)
            {
                $quizes[] = basename($quiz, '.xml');
            }
            return $quizes;
        }
    }
    
    class QuizQuestion implements Serializable, Countable, ArrayAccess, IteratorAggregate
    {
        protected $text;
        protected $answers;
        protected $correctIndex;
        protected $rightText;
        protected $rightSound;
        protected $wrongText;
        protected $wrongSound;
        //protected 
    
        public function __construct($text, array $answers, $correctIndex, $rightText, $rightSound, $wrongText, $wrongSound)
        {
            $this->text = $text;
            $this->answers = $answers;
            $this->correctIndex = $correctIndex;
            $this->rightText = $rightText;
            $this->rightSound = $rightSound;
            $this->wrongText = $wrongText;
            $this->wrongSound = $wrongSound;
        }

        public function __toString()
        {
            return $this->text;
        }
        
        public function getText()
        {
            return $this->text;
        }

        public function getRightText()
        {
            return $this->rightText;
        }

        public function getRightSound()
        {
            return dirname($_SERVER['SCRIPT_NAME']) . '/sounds/' . $this->rightSound . '_right.ogg';
        }

        public function getWrongText()
        {
            return $this->wrongText;
        }

        public function getWrongSound()
        {
            return dirname($_SERVER['SCRIPT_NAME']) . '/sounds/' . $this->wrongSound . '_wrong.ogg';
        }
        
        public function getAnswers()
        {
            return $this->answers;
        }

        public function getCorrectAnswer()
        {
            return $this[$this->correctIndex];
        }
        
        public function getAnswer($index)
        {
            if (isset($this->answers[$index]))
            {
                return $this->answers[$index];
            }
            else
            {
                return null;
            }
        }

        public function count()
        {
            return count($this->questions);
        }

        public function getIterator()
        {
            return new ArrayIterator($this->answers);
        }

        public function offsetExists($offset)
        {
            return isset($this->answers[$offset]);
        }

        public function offsetGet($offset)
        {
            return $this->answers[$offset];
        }

        public function offsetSet($offset, $value)
        {}

        public function offsetUnset($offset)
        {}

        public function serialize()
        {
            return serialize(array(
                $this->text,
                $this->answers,
                $this->rightText,
                $this->wrongText,
                $this->correctIndex,
                $this->rightSound,
                $this->wrongSound
            ));
        }

        public function unserialize($serialized)
        {
            $data = unserialize($serialized);
            $this->text = $data[0];
            $this->answers = $data[1];
            $this->rightText = $data[2];
            $this->wrongText = $data[3];
            $this->correctIndex = $data[4];
            $this->rightSound = $data[5];
            $this->wrongSound = $data[6];
        }
    }

    class QuizAnswer implements Serializable
    {
        protected $text;
        protected $correct;

        public function __construct($text, $correct = false)
        {
            $this->text = $text;
            $this->correct = ($correct ? true : false);
        }

        public function __toString()
        {
            return $this->text;
        }

        public function getText()
        {
            return $this->__toString();
        }

        public function isCorrect()
        {
            return $this->correct;
        }

        public function serialize()
        {
            return serialize(array(
                $this->text,
                $this->correct
            ));
        }

        public function unserialize($serialized)
        {
            $data = unserialize($serialized);
            $this->text = $data[0];
            $this->correct = $data[1];
        }
    }

?>
