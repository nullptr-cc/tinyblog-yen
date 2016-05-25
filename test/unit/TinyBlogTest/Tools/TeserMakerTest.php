<?php

namespace TinyBlogTest\Tools;

use TinyBlog\Tools\TeaserMaker;

class TeaserMakerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider data
     */
    public function test($case)
    {
        $data = $this->loadData($case);
        $maker = new TeaserMaker();
        $teaser = $maker->makeTeaser($data->source);
        $this->assertEquals($data->expected, $teaser);
    }

    public function data()
    {
        return [
            ['simple'], ['empty'], ['broken'], ['long']
        ];
    }

    protected function loadData($case)
    {
        $file_path = TCDATA_PATH . '/teaser-maker/' . $case . '.txt';
        $splitted = explode('---- EXPECTED ----', file_get_contents($file_path));
        return (object)[
            'source' => trim($splitted[0]),
            'expected' => isset($splitted[1]) ? trim($splitted[1]) : ''
        ];
    }
}
