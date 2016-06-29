<?php

namespace TinyBlogTest\Article;

use TinyBlog\Article\Article;
use TinyBlog\Article\Content;
use TinyBlog\User\User;

/**
 * @small
 */
class ArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmpty()
    {
        $article = new Article();

        $this->assertNull($article->getId());
        $this->assertNull($article->getTitle());
        $this->assertNull($article->getTeaser());
        $this->assertNull($article->getCreatedAt());
        $this->assertInstanceOf(Content::class, $article->getBody());
        $this->assertEquals('', $article->getBody()->getSource());
        $this->assertEquals('', $article->getBody()->getHtml());
        $this->assertInstanceOf(User::class, $article->getAuthor());
        $this->assertNull($article->getAuthor()->getId());
        $this->assertNull($article->getAuthor()->getNickname());
        $this->assertNull($article->getAuthor()->getUsername());
        $this->assertNull($article->getAuthor()->getPassword());
        $this->assertTrue($article->getAuthor()->isGuest());
    }

    public function testGetters()
    {
        $article = $this->prepareArticle();

        $this->assertEquals(37, $article->id());
        $this->assertEquals(37, $article->getId());
        $this->assertEquals('Title', $article->title());
        $this->assertEquals('Title', $article->getTitle());
        $this->assertEquals('test', $article->teaser());
        $this->assertEquals('test', $article->getTeaser());
        $this->assertEquals(1451618627, $article->created()->getTimestamp());
        $this->assertEquals(1451618627, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $article->body()->getSource());
        $this->assertEquals('**test** foo __bar__', $article->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->body()->getHtml());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->getBody()->getHtml());
        $this->assertEquals(42, $article->author()->getId());
        $this->assertEquals(42, $article->getAuthor()->getId());
    }

    public function testWithId()
    {
        $article = $this->prepareArticle();

        $clone = $article->withId(99);

        $this->assertEquals(99, $clone->getId());
        $this->assertEquals('Title', $clone->getTitle());
        $this->assertEquals('test', $clone->getTeaser());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $clone->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $clone->getBody()->getHtml());
        $this->assertEquals(42, $clone->getAuthor()->getId());

        $this->assertEquals(37, $article->getId());
        $this->assertEquals('Title', $article->getTitle());
        $this->assertEquals('test', $article->getTeaser());
        $this->assertEquals(1451618627, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $article->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->getBody()->getHtml());
        $this->assertEquals(42, $article->getAuthor()->getId());
    }

    public function testWithTitle()
    {
        $article = $this->prepareArticle();

        $clone = $article->withTitle('Changed');

        $this->assertEquals(37, $clone->getId());
        $this->assertEquals('Changed', $clone->getTitle());
        $this->assertEquals('test', $clone->getTeaser());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $clone->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $clone->getBody()->getHtml());
        $this->assertEquals(42, $clone->getAuthor()->getId());

        $this->assertEquals(37, $article->getId());
        $this->assertEquals('Title', $article->getTitle());
        $this->assertEquals('test', $article->getTeaser());
        $this->assertEquals(1451618627, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $article->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->getBody()->getHtml());
        $this->assertEquals(42, $article->getAuthor()->getId());
    }

    public function testWithTeaser()
    {
        $article = $this->prepareArticle();

        $clone = $article->withTeaser('teaser');

        $this->assertEquals(37, $clone->getId());
        $this->assertEquals('Title', $clone->getTitle());
        $this->assertEquals('teaser', $clone->getTeaser());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $clone->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $clone->getBody()->getHtml());
        $this->assertEquals(42, $clone->getAuthor()->getId());

        $this->assertEquals(37, $article->getId());
        $this->assertEquals('Title', $article->getTitle());
        $this->assertEquals('test', $article->getTeaser());
        $this->assertEquals(1451618627, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $article->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->getBody()->getHtml());
        $this->assertEquals(42, $article->getAuthor()->getId());
    }

    public function testWithCreateAt()
    {
        $article = $this->prepareArticle();

        $clone = $article->withCreatedAt(new \DateTimeImmutable('@1456789012'));

        $this->assertEquals(37, $clone->getId());
        $this->assertEquals('Title', $clone->getTitle());
        $this->assertEquals('test', $clone->getTeaser());
        $this->assertEquals(1456789012, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $clone->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $clone->getBody()->getHtml());
        $this->assertEquals(42, $clone->getAuthor()->getId());

        $this->assertEquals(37, $article->getId());
        $this->assertEquals('Title', $article->getTitle());
        $this->assertEquals('test', $article->getTeaser());
        $this->assertEquals(1451618627, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $article->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->getBody()->getHtml());
        $this->assertEquals(42, $article->getAuthor()->getId());
    }

    public function testWithBody()
    {
        $article = $this->prepareArticle();

        $clone = $article->withBody(new Content('**foo** bar', '<b>foo</b> bar'));

        $this->assertEquals(37, $clone->getId());
        $this->assertEquals('Title', $clone->getTitle());
        $this->assertEquals('test', $clone->getTeaser());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals('**foo** bar', $clone->getBody()->getSource());
        $this->assertEquals('<b>foo</b> bar', $clone->getBody()->getHtml());
        $this->assertEquals(42, $clone->getAuthor()->getId());

        $this->assertEquals(37, $article->getId());
        $this->assertEquals('Title', $article->getTitle());
        $this->assertEquals('test', $article->getTeaser());
        $this->assertEquals(1451618627, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $article->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->getBody()->getHtml());
        $this->assertEquals(42, $article->getAuthor()->getId());
    }

    public function testWithAuthor()
    {
        $article = $this->prepareArticle();

        $clone = $article->withAuthor(new User(['id' => 999]));

        $this->assertEquals(37, $clone->getId());
        $this->assertEquals('Title', $clone->getTitle());
        $this->assertEquals('test', $clone->getTeaser());
        $this->assertEquals(1451618627, $clone->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $clone->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $clone->getBody()->getHtml());
        $this->assertEquals(999, $clone->getAuthor()->getId());

        $this->assertEquals(37, $article->getId());
        $this->assertEquals('Title', $article->getTitle());
        $this->assertEquals('test', $article->getTeaser());
        $this->assertEquals(1451618627, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('**test** foo __bar__', $article->getBody()->getSource());
        $this->assertEquals('<b>test</b> foo <u>bar</u>', $article->getBody()->getHtml());
        $this->assertEquals(42, $article->getAuthor()->getId());
    }

    private function prepareArticle()
    {
        $author = new User([
            'id' => 42,
            'nickname' => 'Foo Bar',
            'username' => 'foobar'
        ]);

        $article = new Article([
            'id' => 37,
            'title' => 'Title',
            'teaser' => 'test',
            'body' => new Content('**test** foo __bar__', '<b>test</b> foo <u>bar</u>'),
            'created_at' => new \DateTimeImmutable('@1451618627'),
            'author' => $author
        ]);

        return $article;
    }
}
