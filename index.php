<?php

// 1.1 An evil clown
function evilClown(String $string)
{
    $array = str_split($string);
//    $newArray = [];
    $newArray[] = $array[0];
    for ($i = 1; $i < count($array); $i++) {
        if (!(parenthesisInArray($array[$i]) && parenthesisInArray($array[$i - 1]))) {
            $newArray[] = $array[$i];
        }

    }
    return implode($newArray);
}

function parenthesisInArray($char)
{
    $parentheses = [
        '(',
        ')',
        '{',
        '}',
        '[',
        ']'
    ];
    if (in_array($char, $parentheses)) {
        return true;
    }
}

echo evilClown('((8)}}:-))))');
echo '<br>';

// 1.2 Lucky Tickets
function allLuckyTickets()
{
    $result = [];
    for ($i = 0; $i < 1000; $i++) {
        for ($k = 0; $k < 1000; $k++) {
            $ticketNumberPart1Array = str_split(sprintf("%'.03d\n", $i));
            $ticketNumberPart2Array = str_split(sprintf("%'.03d\n", $k));
            if (array_sum($ticketNumberPart1Array) == array_sum($ticketNumberPart2Array)) {
                $result[] = sprintf("%'.03d", $i) . sprintf("%'.03d", $k);
            }
        }
    }
    return $result;
}
echo '<pre>';
// echo var_dump(allLuckyTickets());
echo '<br>';
//echo count(allLuckyTickets());

// 2.1 Reverse string
function reverseStrimg(string $string)
{
    $reversedString = '';
    for ($i = strlen($string) - 1; $i > -1; $i--) {
        $reversedString .= $string[$i];
    }
    return $reversedString;
}

echo '<br>';
echo reverseStrimg('Hello world!');

// 2.2 Words in text
function getWords(string $text)
{
    $array = str_word_count(strtolower($text), 1);
    $freq = array_count_values($array);
    arsort($freq);

    return $freq;
}

echo '<br>';
echo '<pre>';
//var_dump(getWords('After several days of rain that nearly floods Derry, Maine, five-year-old Georgie
//Denbrough goes outside to play. He brings with him a paper boat his older brother, Bill,
//made for him while sick in bed. Georgie helps with the boat by retrieving a box of paraffin from the shelves by the cellar stairs, even though the power is out and Georgie
//imagines a monster lives in the cellar. Bill melts the paraffin in a bowl, and the boys
//laugh and joke together while they smear the paraffin over the folded newspaper boat to
//waterproof it. Before Georgie goes out to play, he kisses Bill\'s cheek, and Bill tells
//Georgie to be careful.
//Even though the rains have slackened, the gutters run with water. In his yellow hat and
//slicker, Georgie follows his boat as it sails along Witcham Street, wishing Bill could be
//with him to see it go because Georgie is not as good as Bill at telling stories about what
//he sees. A current takes the boat into a storm drain, and Georgie peeks inside to look
//for it.
//    In the drain Georgie sees a pair of yellow eyes. Scared, he begins to back away, but a
//voice speaks to him. Georgie looks back and sees a clown; the clown introduces himself
//as "Mr. Bob Gray, also known as Pennywise the Dancing Clown." Georgie at first
//thought the drain smelled like his scary cellar at home, but now he can smell a carnival.
//Pennywise has Georgie\'s boat in one hand and a balloon in the other; he offers Georgie
//both. When Georgie reaches forward, Pennywise grabs Georgie\'s arm and rips it off.
//Georgie dies immediately. Dave Gardner, arriving "only 45 seconds after the first
//scream," finds Georgie\'s body. The sheriff tells reporters Georgie must have got his arm stuck in a fast current in the
//storm drain. Mrs. Denbrough is sedated in the local emergency room; Bill remains sick
//in bed, listening to his father weep in another room. The paper boat continues through
//the sewers to the Penobscot River and out to sea as the rain clouds break overhead.'));

// 2.3 Array Sum
function sumOfNestedArray(array $array)
{
    $sum = 0;
    foreach ($array as $item) {
        if (is_array($item)) {
            $sum += sumOfNestedArray($item);
        } else {
            $sum += $item;
        }
    }
    return $sum;
}

var_dump(sumOfNestedArray([[3, [2, -8, 5], 1], 3, 0, -7]));

// 2.4 Mirror Letters
function mirrorLetters(string $string)
{
    $set = [];
    for ($i = 97; $i < 123; $i++) {
        $set[chr($i)] = chr((112-$i)+107);
        $set[strtoupper(chr($i))] = strtoupper(chr((112-$i)+107));
    }
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $chr = $string[$i];
        if (array_key_exists($chr, $set)) {
            $result .= $set[$chr];
        } else {
            $result .= $chr;
        }
    }
    return $result;
}

var_dump(mirrorLetters('AbCdEfG'));

// 2.5 Unique words
function getUniqueWords(string $text)
{
    $wordsArray = getWords($text);
    $uniqueWords = [];
    foreach ($wordsArray as $word => $quantity) {
        if ($quantity === 1) {
            $uniqueWords[] = $word;
        }
    }
    return $uniqueWords;
}

// 3.1 Collection
// polyfill
if (!function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }

        return array_keys($array)[count($array)-1];
    }
}

class Collection
{
    /** @var mixed[string] */
    private $storage = [];

    /** @var object[string] */
    private $objectStorage = [];

    /** @var string[] */
    private $history = [];

    public function add(object $key, $data = null)
    {
        $hash = $this->getHash($key);
        $this->storage[$hash] = $data;
        $this->objectStorage[$hash] = $key;
        $this->history[] = $hash;
    }

    public function remove(object $key)
    {
        $hash = $this->getHash($key);
        if (in_array($hash, $this->history)) {
            unset($this->storage[$hash]);
            unset($this->objectStorage[$hash]);
            unset($this->history[array_search($hash, $this->history)]);
        }
    }

    public function current()
    {
        if (empty($this->history)) {
            return null;
        }
        return $this->objectStorage[$this->history[array_key_last($this->history)]];
    }

    public function getObjectList()
    {
        return array_values($this->objectStorage);
    }

    public function check(object $object)
    {
        $hash = $this->getHash($object);
        return in_array($hash, $this->history);
    }

    public static function removeAll(Collection $collection)
    {
        $objects = $collection->getObjectList();
        foreach ($objects as $object) {
            $collection->remove($object);
        }
    }

    /**
     * @param object $object
     * @return string
     */
    public function getHash(object $object)
    {
        return spl_object_hash($object);
    }
}

// 3.2 Zoo
interface Animal
{
    public function getName(): string;
    public function getAge(): int;
    public function getHealth(): int;
    public function getMass(): int;
    public function getSize(): string;
}

interface Predators
{
    public function attack(Animal $other);

    public function eat(Animal $other);

    public function getAttackDamage(): int;
}

trait PredatorsTrait
{
    public function attack(Animal $other)
    {
        $other->getHealth() - $this->getAttackDamage();
    }

    public function eat(Animal $other)
    {
        $other->getHealth() == 0;
    }
}

interface Herbivores
{
    public function runAway();

    public function eat();
}

abstract class Mammals implements Animal
{
    public function getPawsCount(): int
    {
        return 4;
    }

    abstract public function getRunSpeed(): int;

}

class Cat extends Mammals implements Predators
{
    use PredatorsTrait;


    public function getName(): string
    {
        return 'Cat';
    }

    public function getAge(): int
    {
        return 10;
    }

    public function getHealth(): int
    {
        return 50;
    }

    public function getMass(): int
    {
        return 100;
    }

    public function getSize(): string
    {
        return 'Small';
    }

    public function getAttackDamage(): int
    {
        return 20;
    }

    public function getRunSpeed(): int
    {
        return 50;
    }
}

class Cougar extends Cat
{
    public function getName(): string
    {
        return 'Cougar';
    }

    public function getAge(): int
    {
        return 20;
    }

    public function getHealth(): int
    {
        return parent::getHealth() + 100;
    }

    public function getMass(): int
    {
        return 80;
    }

    public function getSize(): string
    {
        return 'medium';
    }

    public function getAttackDamage(): int
    {
        return parent::getAttackDamage() + 60;
    }

    public function getRunSpeed(): int
    {
        return parent::getRunSpeed() + 100;
    }

}

class Lion extends Cat
{
    public function getName(): string
    {
        return 'Loin';
    }

    public function getAge(): int
    {
        return 30;
    }

    public function getHealth(): int
    {
        return parent::getHealth() + 110;
    }

    public function getMass(): int
    {
        return 90;
    }

    public function getSize(): string
    {
        return 'medium';
    }

    public function getAttackDamage(): int
    {
        return parent::getAttackDamage() + 70;
    }

    public function getRunSpeed(): int
    {
        return parent::getRunSpeed() + 60;
    }

}

class Wolf extends Mammals implements Predators
{
    use PredatorsTrait;

    public function getName(): string
    {
        return 'Wolf';
    }

    public function getAge(): int
    {
        return 6;
    }

    public function getHealth(): int
    {
        return 40;
    }

    public function getMass(): int
    {
        return 40;
    }

    public function getSize(): string
    {
        return 'small-medium';
    }

    public function getAttackDamage(): int
    {
        return 30;
    }

    public function getRunSpeed(): int
    {
        return 40;
    }

}

class Dog extends Wolf
{
    public function eat(Animal $other)
    {
        $food = 'meal';
    }

    public function getName(): string
    {
        return 'Dog';
    }

    public function getAttackDamage(): int
    {
        return parent::getAttackDamage() - 10;
    }

    public function playWithHumans()
    {
        'plays';
    }
}

trait MammalsHerbivoresTrait
{
    public function eat()
    {
        $food = 'grass';
    }

    public function runAway()
    {
        $this->getRunSpeed();
    }
}

class Antilopa extends Mammals implements Herbivores
{
    use MammalsHerbivoresTrait;

    public function getName(): string
    {
        return 'Antilopa';
    }

    public function getAge(): int
    {
        return 20;
    }

    public function getHealth(): int
    {
        return 80;
    }

    public function getMass(): int
    {
        return 70;
    }

    public function getSize(): string
    {
        return 'medium';
    }

    public function getRunSpeed(): int
    {
        return 100;
    }
}

class Elephant extends Mammals implements Herbivores
{
    use MammalsHerbivoresTrait;
    public function getName(): string
    {
        return 'Elephant';
    }

    public function getAge(): int
    {
        return 30;
    }

    public function getHealth(): int
    {
        return 100;
    }

    public function getMass(): int
    {
        return 150;
    }

    public function getSize(): string
    {
        return 'large';
    }

    public function getRunSpeed(): int
    {
        return 30;
    }

}

abstract class Insect implements Animal
{
    abstract public function getLegsCount(): int;
    abstract public function hasWings(): bool;

    public function getMass(): int
    {
        return 1;
    }

    public function getSize(): string
    {
        return 'extremely small';
    }

}

abstract class Fish implements Animal
{
    abstract public function getSwimSpeed(): int;
}

class Shark extends Fish implements Predators
{
    use PredatorsTrait;

    public function getName(): string
    {
        return 'Shark';
    }

    public function getAge(): int
    {
        return 40;
    }

    public function getHealth(): int
    {
        return 110;
    }

    public function getMass(): int
    {
        return 100;
    }

    public function getSize(): string
    {
        return 'medium-large';
    }

    public function getAttackDamage(): int
    {
        return 180;
    }

    public function getSwimSpeed(): int
    {
        return 200;
    }


}

class Trout extends Fish implements Herbivores
{
    public function getName(): string
    {
        return 'Trout';
    }

    public function getAge(): int
    {
        return 10;
    }

    public function getHealth(): int
    {
        return 60;
    }

    public function getMass(): int
    {
        return 20;
    }

    public function getSize(): string
    {
        return 'small';
    }

    public function runAway()
    {
        $this->getSwimSpeed();
    }

    public function eat()
    {
        $food = 'river food';
    }

    public function getSwimSpeed(): int
    {
        return 100;
    }

}
