<?php

namespace Rocket;

class Validation
{
    private $data;
    private $error = [];

    public function set($value, $name = null)
    {
        $this->data = ["value" => trim($value), "name" => trim($name)];
        return $this;
    }

    public function is_required($msg)
    {
        if(empty($this->data['value'])):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function is_email($msg)
    {
        if(!filter_var($this->data['value'], FILTER_VALIDATE_EMAIL)):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function is_date($msg)
    {
        if(!preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",$this->data['value'])):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function is_phone($msg)
    {
        if(!preg_match("/^\([0-9]{2}\)[0-9]{5}\-[0-9]{4}$/",$this->data['value'])):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function cpf($msg)
    {
        if(!preg_match("/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/",$this->data['value'])):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function is_min($char, $msg)
    {
        if(strlen($this->data['value']) < $char):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function is_max($char, $msg)
    {
        if(strlen($this->data['value']) > $char):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function is_between($min,$max, $msg)
    {
        if((strlen($this->data['value']) < $min) || (strlen($this->data['value']) > $max)):
            $this->error[] = $this->data['name'].' '.$msg;
        endif;
        return $this;
    }

    public function validate()
    {
        if(count($this->error) > 0):
            return true;
        else:
            return false;
        endif;
    }

    public function getError()
    {
        $e = '';
        foreach($this->error as $v):
            $e .= "\n".'<li>'.$v.'</li>';
        endforeach;
        echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><b><i class="fa fa-exclamation-circle"></i> Atenção</b><ul>'.$e."\n".'</ul></div>'."\n";
    }
}