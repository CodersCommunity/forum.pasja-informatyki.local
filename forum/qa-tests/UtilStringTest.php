<?php
require_once QA_INCLUDE_DIR.'util/string.php';

use PHPUnit\Framework\TestCase;

class UtilStringTest extends TestCase
{
	private $strBasic = 'So I tied an onion to my belt, which was the style at the time.';
	private $strAccents = 'Țĥé qũīçĶ ßřǭŴƞ Ƒöŧ ǰÙƢƥş ØƲĘŕ ƮĦȨ ĿÆƶȳ Ƌơǥ';
	private $blockWordString = 't*d o*n b*t style';

	public function test__qa_string_to_words(): void
    {
		$test1 = qa_string_to_words($this->strBasic);
		$expected1 = ['so', 'i', 'tied', 'an', 'onion', 'to', 'my', 'belt', 'which', 'was', 'the', 'style', 'at', 'the', 'time'];

		$test2 = qa_string_to_words($this->strBasic, false);
		$expected2 = ['So', 'I', 'tied', 'an', 'onion', 'to', 'my', 'belt', 'which', 'was', 'the', 'style', 'at', 'the', 'time'];

		$this->assertEquals($expected1, $test1);
		$this->assertEquals($expected2, $test2);
	}

	public function test__qa_string_remove_accents(): void
    {
		$test = qa_string_remove_accents($this->strAccents);
		$expected = 'The quicK ssroWn Fot jUOIps OVEr THE LAEzy Dog';

		$this->assertEquals($expected, $test);
	}

	public function test__qa_tags_to_tagstring(): void
    {
		$test = qa_tags_to_tagstring(['Hello', 'World']);
		$expected = 'Hello,World';

		$this->assertEquals($expected, $test);
	}

	public function test__qa_tagstring_to_tags(): void
    {
		$test = qa_tagstring_to_tags('hello,world');
		$expected = ['hello', 'world'];

		$this->assertEquals($expected, $test);
	}

	public function test__qa_shorten_string_line(): void
    {
		// qa_shorten_string_line ($string, $length)

		$test = qa_shorten_string_line($this->strBasic, 30);

		$this->assertStringStartsWith('So I tied', $test);
		$this->assertStringEndsWith('time.', $test);
		$this->assertNotFalse(strpos($test, '...'));
	}

	public function test__qa_block_words_explode(): void
    {
		$test = qa_block_words_explode($this->blockWordString);
		$expected = ['t*d', 'o*n', 'b*t', 'style'];

		$this->assertEquals($expected, $test);
	}

	public function test__qa_block_words_to_preg(): void
    {
		$test = qa_block_words_to_preg($this->blockWordString);
		$expected = '(?<= )t[^ ]*d(?= )|(?<= )o[^ ]*n(?= )|(?<= )b[^ ]*t(?= )|(?<= )style(?= )';

		$this->assertEquals($expected, $test);
	}

	public function test__qa_block_words_match_all(): void
    {
		$test1 = qa_block_words_match_all('onion belt', '');

		$wordpreg = qa_block_words_to_preg($this->blockWordString);
		$test2 = qa_block_words_match_all('tried an ocean boat', $wordpreg);
		// matches are returned as array of [offset] => [length]
		$expected = [
			 0 => 5, // tried
			 9 => 5, // ocean
			15 => 4, // boat
        ];

		$this->assertEmpty($test1);
		$this->assertEquals($expected, $test2);
	}

	public function test__qa_block_words_replace(): void
    {
		$wordpreg = qa_block_words_to_preg($this->blockWordString);
		$test = qa_block_words_replace('tired of my ocean boat style', $wordpreg);
		$expected = '***** of my ***** **** *****';

		$this->assertEquals($expected, $test);
	}

	public function test__qa_random_alphanum(): void
    {
		$len = 50;
		$test = qa_random_alphanum($len);

		$this->assertEquals(strlen($test), $len);
	}

	public function test__qa_email_validate(): void
    {
		$goodEmails = [
			'hello@example.com',
			'q.a@question2answer.org',
			'example@newdomain.app'
        ];
		$badEmails = [
			'nobody@nowhere',
			'pokémon@example.com',
			'email @ with spaces',
			'some random string',
        ];

		foreach ($goodEmails as $email) {
			$this->assertTrue( qa_email_validate($email) );
		}
		foreach ($badEmails as $email)
			$this->assertFalse( qa_email_validate($email) );
	}

	public function test__qa_strlen(): void
    {
		$test = qa_strlen($this->strAccents);

		$this->assertEquals($test, 43);
	}

	public function test__qa_strtolower(): void
    {
		$test = qa_strtolower('hElLo WoRld');

		$this->assertEquals($test, 'hello world');
	}

	public function test__qa_substr(): void
    {
		$test = qa_substr($this->strBasic, 5, 24);

		$this->assertEquals($test, 'tied an onion to my belt');
	}

	public function test__qa_string_matches_one(): void
    {
		$matches = ['dyed', 'shallot', 'belt', 'fashion'];
		$nonMatches = ['dyed', 'shallot', 'buckle', 'fashion'];

		$this->assertTrue( qa_string_matches_one($this->strBasic, $matches) );
		$this->assertFalse( qa_string_matches_one($this->strBasic, $nonMatches) );
	}
}
