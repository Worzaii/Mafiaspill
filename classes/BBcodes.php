<?php


class BBcodes
{
    public string $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Applies all formattings to text for less messy code
     * @return string
     */
    public function applyAllBBcodes()
    {
        if (strlen($this->text) == 0) {
            return "";
        }
        return $this->h()->c()->i()->u()->b()->ce()->hr()->l1()->l2()->n()->p()->s()->sd()->y()->text;
    }

    /**
     * Applies embed youtube video to text, autoplay option removed
     * @return $this
     */
    public function y()
    {
        $this->text = preg_replace(
            "/\[youtube=([a-z0-9-_]+)\]/is",
            "<iframe style=\"min-width: 100%;height: 315px;\" src=\"https://www.youtube-nocookie.com/embed/$1\" frameborder=\"0\" allow=\"accelerometer; clipboard-write; encrypted-media; gyroscope;\" allowfullscreen=\"false\"></iframe>",
            $this->text
        );
        return $this;
    }

    /**
     * Applies either black shadow or defined color shadown to text.
     * @return $this
     */
    public function sd()
    {
        $this->text = preg_replace(
            ["/\[s1\](.*?)\[\/s1\]/is", "/\[s2 color=\"#(([0-9a-f]){3}|([0-9a-f]){6})\"\](.*?)\[\/s2\]/is"],
            [
                "<span style=\"text-shadow:none;text-shadow: #000000 2px 2px 2px;\">$1</span>",
                "<span style=\"text-shadow:none;text-shadow: #$1 2px 2px 2px;\">$4</span>"
            ],
            $this->text
        );
        return $this;
    }

    /**
     * Applies Spotify song formatting to text
     * @return $this
     */
    public function s()
    {
        $this->text = preg_replace(
            "#\[spotify=([a-z0-9_-]+)\]#is",
            "<iframe src=\"https://open.spotify.com/embed/track/$1\" width=\"300\" height=\"380\" frameborder=\"0\" allowtransparency=\"true\" allow=\"encrypted-media\"></iframe>",
            $this->text
        );
        return $this;
    }

    /**
     * Applies picture formatting to text
     * @return $this
     */
    public function p()
    {
        $this->text = preg_replace(
            "#\[img=htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)]#",
            "<img src=\"htt$1$2\" style=\"max-width:100%;\" alt=\"\">",
            $this->text
        );
        return $this;
    }

    /**
     * Applies linejump to text
     * @return $this
     */
    public function n()
    {
        $this->text = str_replace("\n", "<br>", $this->text);
        return $this;
    }

    /**
     * Applies default link-formatting to text
     * @return $this
     */
    public function l2()
    {
        $this->text = preg_replace(
            '#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$@&()\'*+,;=%]+)"\]#i',
            '<a href="htt$1$2">htt$1$2</a>',
            $this->text
        );
        return $this;
    }

    /**
     * Applies custom-link with title to text
     * @return $this
     */
    public function l1()
    {
        $this->text = preg_replace(
            '#\[link="htt(p://|ps://)([A-Za-z0-9\-._~:/?\#[\]@!$&()\'*+,;=%]+)" text="(.+)"\]#',
            '<a href="htt$1$2" title="htt$1$2">$3</a>',
            $this->text
        );
        return $this;
    }

    /**
     * Applies horizontal line to text
     * @return $this
     */
    public function hr()
    {
        $this->text = preg_replace("/(.*?)\[hr\](.*?)/is", "$1<hr>$2", $this->text);
        return $this;
    }

    /**
     * Applies center formatting to text
     * @return $this
     */
    public function ce()
    {
        $this->text = preg_replace(
            "/\[center\](.*?)\[\/center\]/is",
            "<div style='text-align:center;'>$1</div>",
            $this->text
        );
        return $this;
    }

    /**
     * Applies bold formatting to text
     * @return $this
     */
    public function b()
    {
        $this->text = preg_replace(
            "/\[b\](.*?)\[\/b\]/is",
            "<span style='font-weight:bold'>$1</span>",
            $this->text
        );
        return $this;
    }

    /**
     * Applies underline formatting to text
     * @return $this
     */
    public function u()
    {
        $this->text = preg_replace(
            "/\[u\](.*?)\[\/u\]/is",
            "<span style='text-decoration:underline;'>$1</span>",
            $this->text
        );
        return $this;
    }

    /**
     * Applies italic formatting to text
     * @return $this
     */
    public function i()
    {
        $this->text = preg_replace(
            "/\[i\](.*?)\[\/i\]/is",
            "<span style='font-style:italic'>$1</span>",
            $this->text
        );
        return $this;
    }

    /**
     * Applies color formatting to text, HEX only
     * @return $this
     */
    public function c()
    {
        $this->text = preg_replace(
            "/\[color=#(([0-9a-f]){3}|([0-9a-f]){6})\](.*?)\[\/color\]/is",
            "<span style=\"color:#$1\">$4</span>",
            $this->text
        );
        return $this;
    }

    /**
     * Applies htmlentities to text
     * @return $this
     */
    public function h()
    {
        $this->text = htmlentities($this->text, ENT_NOQUOTES | ENT_HTML401, 'UTF-8');
        return $this;
    }
}
