<?php

namespace Fpay\Xid;

class Xid
{
    const ENCODED_LEN = 20;

    const DECODED_LEN = 15;

    const RAW_LEN = 12;

    // encoding stores a custom version of the base32 encoding with lower case letters.
    const ENCODING = "0123456789abcdefghijklmnopqrstuv";

    /**
     * @var array
     */
    public $value;

    /**
     * __construct
     *
     * @param array $id
     */
    public function __construct($id)
    {
        $this->value = $id;
    }

    /**
     * pid
     *
     * @return int
     */
    public function pid()
    {
        return ($this->value[7] << 8 | $this->value[8]);
    }

    /**
     * counter
     *
     * @return int
     */
    public function counter()
    {
        return ($this->value[9] << 16 | $this->value[10] << 8 | $this->value[11]);
    }

    /**
     * machine
     *
     * @return string
     */
    public function machine()
    {
        return implode(array_map(function($v) {
            return chr($v);
        }, array_slice($this->value, 4, 3)));
    }

    /**
     * time
     *
     * @return int
     */
    public function time()
    {
        return ($this->value[0] << 24 | $this->value[1] << 16 | $this->value[2] << 8 | $this->value[3]);
    }

    public function __toString()
    {
        $text = self::encode($this->value);
        return implode($text);
    }

    /**
     * encode
     *
     * @param array $id
     * @return array
     */
    private static function encode($id)
    {
        $dst = [];

        $dst[0] = self::ENCODING[$id[0] >> 3];
	    $dst[1] = self::ENCODING[($id[1] >> 6) & 0x1f | ($id[0] << 2) & 0x1f];
	    $dst[2] = self::ENCODING[($id[1] >> 1) & 0x1f];
	    $dst[3] = self::ENCODING[($id[2] >> 4) & 0x1f | ($id[1] << 4) & 0x1f];
	    $dst[4] = self::ENCODING[$id[3] >> 7 | ($id[2] << 1) & 0x1f];
	    $dst[5] = self::ENCODING[($id[3] >> 2) & 0x1f];
	    $dst[6] = self::ENCODING[$id[4] >> 5 | ($id[3] << 3) & 0x1f];
	    $dst[7] = self::ENCODING[$id[4] & 0x1f];
	    $dst[8] = self::ENCODING[$id[5] >> 3];
	    $dst[9] = self::ENCODING[($id[6] >> 6) & 0x1f | ($id[5] << 2) & 0x1f];
	    $dst[10] = self::ENCODING[($id[6] >> 1) & 0x1f];
	    $dst[11] = self::ENCODING[($id[7] >> 4) & 0x1f | ($id[6] << 4) & 0x1f];
	    $dst[12] = self::ENCODING[$id[8] >> 7 | ($id[7] << 1) & 0x1f];
	    $dst[13] = self::ENCODING[($id[8] >> 2) & 0x1f];
	    $dst[14] = self::ENCODING[($id[9] >> 5) | ($id[8] << 3) & 0x1f];
	    $dst[15] = self::ENCODING[$id[9] & 0x1f];
	    $dst[16] = self::ENCODING[$id[10] >> 3];
	    $dst[17] = self::ENCODING[($id[11] >> 6) & 0x1f | ($id[10] << 2) & 0x1f];
	    $dst[18] = self::ENCODING[($id[11] >> 1) & 0x1f];
	    $dst[19] = self::ENCODING[($id[11] << 4) & 0x1f];

        return $dst;
    }
}
