<?php
namespace Alexya\Tools;

/**
 * Inflector class.
 *
 * This class offers helpers for pluralizing and singularizing
 * english words.
 *
 * To pluralize a word use the method `plural`, it accepts
 * as parameter the word to pluralize.
 *
 * To singularize a word use the method `singular`, it accepts
 * as parameter the word to singularize.
 *
 * This class contains code from the [Doctrine project](http://www.doctrine-project.org) coded
 * by {@author Konsta Vesterinen <kvesteri@cc.hut.fi>} and {@author Jonathan H. Wage <jonwage@gmail.com>}.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Inflector
{

    /**
     * Plural inflector rules.
     *
     * @var array
     */
    private static $_plural = [
        'rules' => [
            '/(s)tatus$/i'                                                           => '\1\2tatuses',
            '/(quiz)$/i'                                                             => '\1zes',
            '/^(ox)$/i'                                                              => '\1\2en',
            '/([m|l])ouse$/i'                                                        => '\1ice',
            '/(matr|vert|ind)(ix|ex)$/i'                                             => '\1ices',
            '/(x|ch|ss|s)]$/i'                                                       => '\1es',
            '/([^aeiouy]|qu)y$/i'                                                    => '\1ies',
            '/(hive)$/i'                                                             => '\1s',
            '/(?:([^f])fe|([lr])f)$/i'                                               => '\1\2ves',
            '/sis$/i'                                                                => 'ses',
            '/([ti])um$/i'                                                           => '\1a',
            '/(p)erson$/i'                                                           => '\1eople',
            '/(m)an$/i'                                                              => '\1en',
            '/(c)hild$/i'                                                            => '\1hildren',
            '/(f)oot$/i'                                                             => '\1eet',
            '/(buffal|her|potat|tomat|volcan)o$/i'                                   => '\1\2oes',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
            '/us$/i'                                                                 => 'uses',
            '/(alias)$/i'                                                            => '\1es',
            '/(analys|ax|cris|test|thes)is$/i'                                       => '\1es',
            '/s$/'                                                                   => 's',
            '/^$/'                                                                   => '',
            '/$/'                                                                    => 's'
        ],
        'uninflected' => [
            '.*[nrlm]ese',
            '.*deer',
            '.*fish',
            '.*measles',
            '.*ois',
            '.*pox',
            '.*sheep',
            'people',
            'cookie'
        ],
        'irregular' => [
            'atlas'        => 'atlases',
            'axe'          => 'axes',
            'beef'         => 'beefs',
            'brother'      => 'brothers',
            'cafe'         => 'cafes',
            'chateau'      => 'chateaux',
            'child'        => 'children',
            'cookie'       => 'cookies',
            'corpus'       => 'corpuses',
            'cow'          => 'cows',
            'criterion'    => 'criteria',
            'curriculum'   => 'curricula',
            'demo'         => 'demos',
            'domino'       => 'dominoes',
            'echo'         => 'echoes',
            'foot'         => 'feet',
            'fungus'       => 'fungi',
            'ganglion'     => 'ganglions',
            'genie'        => 'genies',
            'genus'        => 'genera',
            'graffito'     => 'graffiti',
            'hippopotamus' => 'hippopotami',
            'hoof'         => 'hoofs',
            'human'        => 'humans',
            'iris'         => 'irises',
            'leaf'         => 'leaves',
            'loaf'         => 'loaves',
            'man'          => 'men',
            'medium'       => 'media',
            'memorandum'   => 'memoranda',
            'money'        => 'monies',
            'mongoose'     => 'mongooses',
            'motto'        => 'mottoes',
            'move'         => 'moves',
            'mythos'       => 'mythoi',
            'niche'        => 'niches',
            'nucleus'      => 'nuclei',
            'numen'        => 'numina',
            'occiput'      => 'occiputs',
            'octopus'      => 'octopuses',
            'opus'         => 'opuses',
            'ox'           => 'oxen',
            'penis'        => 'penises',
            'person'       => 'people',
            'plateau'      => 'plateaux',
            'runner-up'    => 'runners-up',
            'sex'          => 'sexes',
            'soliloquy'    => 'soliloquies',
            'son-in-law'   => 'sons-in-law',
            'syllabus'     => 'syllabi',
            'testis'       => 'testes',
            'thief'        => 'thieves',
            'tooth'        => 'teeth',
            'tornado'      => 'tornadoes',
            'trilby'       => 'trilbys',
            'turf'         => 'turfs',
            'volcano'      => 'volcanoes',
        ]
    ];

    /**
     * Singular inflector rules.
     *
     * From Doctrine
     *
     * @var array
     */
    private static $_singular = [
        'rules' => [
            '/(s)tatuses$/i'                                                          => '\1\2tatus',
            '/^(.*)(menu)s$/i'                                                        => '\1\2',
            '/(quiz)zes$/i'                                                           => '\\1',
            '/(matr)ices$/i'                                                          => '\1ix',
            '/(vert|ind)ices$/i'                                                      => '\1ex',
            '/^(ox)en/i'                                                              => '\1',
            '/(alias)(es)*$/i'                                                        => '\1',
            '/(buffal|her|potat|tomat|volcan)oes$/i'                                  => '\1o',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '\1us',
            '/([ftw]ax)es/i'                                                          => '\1',
            '/(analys|ax|cris|test|thes)es$/i'                                        => '\1is',
            '/(shoe|slave)s$/i'                                                       => '\1',
            '/(o)es$/i'                                                               => '\1',
            '/ouses$/'                                                                => 'ouse',
            '/([^a])uses$/'                                                           => '\1us',
            '/([m|l])ice$/i'                                                          => '\1ouse',
            '/(x|ch|ss|sh)es$/i'                                                      => '\1',
            '/(m)ovies$/i'                                                            => '\1\2ovie',
            '/(s)eries$/i'                                                            => '\1\2eries',
            '/([^aeiouy]|qu)ies$/i'                                                   => '\1y',
            '/([lr])ves$/i'                                                           => '\1f',
            '/(tive)s$/i'                                                             => '\1',
            '/(hive)s$/i'                                                             => '\1',
            '/(drive)s$/i'                                                            => '\1',
            '/([^fo])ves$/i'                                                          => '\1fe',
            '/(^analy)ses$/i'                                                         => '\1sis',
            '/(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'             => '\1\2sis',
            '/([ti])a$/i'                                                             => '\1um',
            '/(p)eople$/i'                                                            => '\1\2erson',
            '/(m)en$/i'                                                               => '\1an',
            '/(c)hildren$/i'                                                          => '\1\2hild',
            '/(f)eet$/i'                                                              => '\1oot',
            '/(n)ews$/i'                                                              => '\1\2ews',
            '/eaus$/'                                                                 => 'eau',
            '/^(.*us)$/'                                                              => '\\1',
            '/s$/i'                                                                   => ''
        ],
        'uninflected' => [
            '.*[nrlm]ese',
            '.*deer',
            '.*fish',
            '.*measles',
            '.*ois',
            '.*pox',
            '.*sheep',
            '.*ss',
        ],
        'irregular' => [
            'criteria'  => 'criterion',
            'curves'    => 'curve',
            'emphases'  => 'emphasis',
            'foes'      => 'foe',
            'hoaxes'    => 'hoax',
            'media'     => 'medium',
            'neuroses'  => 'neurosis',
            'waves'     => 'wave',
            'oases'     => 'oasis',
        ]
    ];

    /**
     * Words that should not be inflected.
     *
     * @var array
     */
    private static $_uncontable = [
        'Amoyese',
        'bison',
        'Borghese',
        'bream',
        'breeches',
        'britches',
        'buffalo',
        'cantus',
        'carp',
        'chassis',
        'clippers',
        'cod',
        'coitus',
        'Congoese',
        'contretemps',
        'corps',
        'debris',
        'diabetes',
        'djinn',
        'eland',
        'elk',
        'equipment',
        'Faroese',
        'flounder',
        'Foochowese',
        'gallows',
        'Genevese',
        'Genoese',
        'Gilbertese',
        'graffiti',
        'headquarters',
        'herpes',
        'hijinks',
        'Hottentotese',
        'information',
        'innings',
        'jackanapes',
        'Kiplingese',
        'Kongoese',
        'Lucchese',
        'mackerel',
        'Maltese',
        'mews',
        'moose',
        'mumps',
        'Nankingese',
        'news',
        'nexus',
        'Niasese',
        'Pekingese',
        'Piedmontese',
        'pincers',
        'Pistoiese',
        'pliers',
        'Portuguese',
        'proceedings',
        'rabies',
        'rice',
        'rhinoceros',
        'salmon',
        'Sarawakese',
        'scissors',
        'series',
        'Shavese',
        'shears',
        'siemens',
        'species',
        'staff',
        'swine',
        'testes',
        'trousers',
        'trout',
        'tuna',
        'Vermontese',
        'Wenchowese',
        'whiting',
        'wildebeest',
        'Yengeese',
        'audio',
        'compensation',
        'coreopsis',
        'data',
        'deer',
        'education',
        'fish',
        'gold',
        'knowledge',
        'love',
        'rain',
        'money',
        'nutrition',
        'offspring',
        'plankton',
        'police',
        'sheep',
        'traffic'
    ];

    /**
     * Returns the plural form of the word.
     *
     * @param string $word Word to pluralize.
     *
     * @return string The plural form of `$word`.
     */
    public static function plural(string $word) : string
    {
        // Check that `$word` is countable.
        if(in_array($word, Inflector::$_uncontable)) {
            return $word;
        }

        // Now check that `$word` is regular.
        if(isset(Inflector::$_plural["irregular"][$word])) {
            return Inflector::$_plural["irregular"][$word];
        }

        // Now check that `$word` can be pluralize.
        foreach(Inflector::$_plural["uninflected"] as $rule) {
            if(preg_match("/^". $rule ."$/", $word)) {
                return $word;
            }
        }

        // Now the word follows a rule, so apply it
        foreach(Inflector::$_plural["rules"] as $key => $value) {
            if(preg_match($key, $word)) {
                return preg_replace($key, $value, $word);
            }
        }

        // Well, right now the word couldn't be pluralized
        // so return it as it was (or throw an exception?)
        return $word;
    }

    /**
     * Returns the singular form of the word.
     *
     * @param string $word Word to singularize.
     *
     * @return string The singular form of `$word`.
     */
    public static function singular(string $word) : string
    {
        // Check that `$word` is countable.
        if(in_array($word, Inflector::$_uncontable)) {
            return $word;
        }

        // Now check that `$word` is regular.
        if(isset(Inflector::$_singular["irregular"][$word])) {
            return Inflector::$_singular["irregular"][$word];
        }

        // Now check that `$word` can be singularize.
        foreach(Inflector::$_singular["uninflected"] as $rule) {
            if(preg_match("/^". $rule ."$/", $word)) {
                return $word;
            }
        }

        // Now the word follows a rule, so apply it
        foreach(Inflector::$_singular["rules"] as $key => $value) {
            if(preg_match($key, $word)) {
                return preg_replace($key, $value, $word);
            }
        }

        // Well, right now the word couldn't be singularized
        // so return it as it was (or throw an exception?)
        return $word;
    }
}
