<?php


class Crime
{
    private $canDoCrime = false;
    private $timeout = 0;
    private array $availableChoices = [];
    protected PDO $db;
    protected $obj;

    public function __construct(PDO $db, $obj)
    {
        $this->db = $db;
        $this->obj = $obj;
    }

    public function MustWait($user)
    {
        $cquery = $this->db->prepare("select count(*) from krimlogg where uid = ? and timewait > unix_timestamp() limit 1");
        $cquery->execute([$user->id]);
        if($cquery->fetchColumn() == 1){
            $query = $this->db->prepare("select (timewait - unix_timestamp()) as remaining from krimlogg where uid = ? and timewait > unix_timestamp() limit 1");
            $this->timeout = $query->fetchColumn();
        }
        return $this;
    }
}