<?php
/**
 * a tool to delete unreasonable posts automatically.
 * @param
 * @param $config - array
 * @start cloudDel->work()
 * @return
 * @authors fisher (i@qnmlgb.trade)
 * @date    2018-03-07 00:39:25
 * @version 4.0
 */

class cloudDel
{
    protected $kw;
    protected $CLI;
    protected $interval;
    protected $bduss;
    protected $wap;
    protected $block;
    protected $days;
    protected $ad;
    protected $reason;
    protected $bApi = 'http://tieba.baidu.com/pmc/blockid';
    protected $dApi = 'http://tieba.baidu.com/f/commit/thread/delete';
    protected $pApi = 'http://c.tieba.baidu.com/c/f/frs/page';
    protected $tApi = 'http://tieba.baidu.com/dc/common/tbs';
    public function __construct($config)
    {
        array_walk($config, function ($v, $k) {
            return $this->$k = $v;
        });
    }
    public function work()
    {
        if (file_exists('stop')) {
            die('-1: occour an ERROR');
        }
        $f = $this->getContents();
        if ($f['error_code'] != 0) {
            $tip = '获取贴子列表失败,请稍候再试!' . date('y-m-d H:i:s', time()) . "\n";
            $this->output($tip);
            return;
        }
        foreach ($f['thread_list'] as $thread) {
            if ($this->matchAd($thread['title']) || $this->matchAd($thread['abstract'][0]['text'])) {
                if ($this->block) {
                    $this->blockId($thread['author']['name'], $f['forum']['id']);
                }
                $this->delThread($thread['tid'], $f['forum']['id']);
            }
        }
        $tip = "巡逻已结束 time:" . date('y-m-d H:i:s', time()) . "\n";
        $this->output($tip);
    }
    protected function matchAd($text)
    {
        if ($this->ad['str']) {
            foreach ($this->ad['p'] as $v) {
                if (strpos($text, $v) !== false) {
                    return true;
                }
            }
            return false;
        }
        foreach ($ad['p'] as $v) {
            if (preg_match($v, $text)) {
                return true;
            }
        }
        return false;
    }
    protected function getTbs()
    {
        $re = $this->cget($this->tApi, 'BDUSS=' . $this->bduss);
        if (!$re['is_login']) {
            $this->CLI or die("BDUSS失效,请重新获取,程序已自动结束!");
            file_put_contents('./log/ERROR' . date('Y-m-d', time()) . ".txt", "BDUSS失效，请重新获取");
            file_put_contents('stop', "");
            die;
        }
        return $re['tbs'];
    }

    protected function blockId($un, $fid)
    {
        $data = 'day=' . $this->days . '&fid=' . $fid . '&tbs=' . $this->getTbs() . '&ie=gbk&user_name[]=' . $un . '&reason=' . $this->reason;
        $re   = $this->cget($this->bApi, 'BDUSS=' . $this->bduss, $data);
        $tip  = '禁封 ' . $un . ($re['errno'] == 0 ? ' 成功' : ' 失败') . date('y-m-d H:i:s', time()) . "\n";
        $this->output($tip);
    }

    protected function delThread($tid, $fid)
    {
        $d   = 'commit_fr=pb&ie=utf-8&tbs=' . $this->getTbs() . "&kw=" . $this->kw . "&fid=$fid&tid=$tid";
        $re  = $this->cget($this->dApi, 'BDUSS=' . $this->bduss, $d);
        $tip = '删除 ' . $tid . ($re['no'] == 0 ? ' 成功' : ' 失败') . date('y-m-d H:i:s', time()) . "\n";
        $this->output($tip);
    }
    protected function getContents()
    {
        $d = array(
            '_client_id=wappc_1396611108603_817',
            '_client_type=2',
            '_client_version=5.1.0',
            '_phone_imei=860878030000291',
            'from=tieba',
            'kw=' . $this->kw,
            'pn=1',
            'q_type=2',
            'rn=100',
            'with_group=0');
        $d = implode('&', $d) . '&sign=' . md5(implode('', $d) . 'tiebaclient!!!');
        return $this->cget($this->pApi, '', $d);
    }
    protected function output($data)
    {
        if ($this->CLI) {
            echo $data;
            return;
        }
        file_put_contents('./log/lists-' . date('yyyy-mm-dd', time()) . ".txt", $data);
    }
    protected function cget($url, $cookies = '', $post = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $post ? curl_setopt($ch, CURLOPT_POSTFIELDS, $post) : null;
        $cookies ? curl_setopt($ch, CURLOPT_COOKIE, $cookies) : null;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $re = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $re;
    }

}
