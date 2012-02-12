<?php
namespace ToPhar;

class CommandLine
{
    protected $pairs = array();
    
    protected $args  = array();
    
    protected $required = array();
    
    protected $optional = array();
    
    public function addPair($short, $long, $required = false, $optional = false)
    {
        $this->pairs[$short] = $long;
        
        if ($optional)
        {
            $this->optional[$short] = true;
        }
        
        if ($required)
        {
            $this->required[$short] = true;
        }
    }
    
    public function match()
    {
        $short = "";
        $long  = array();
        
        foreach ($this->pairs as $key => $value)
        {
            $flags = (isset($this->required[$key]) ? ":" : "");
            $flags .= (isset($this->optional[$key]) ? "::" : "");
            
            $short .= "$key$flags";
            $long[] = "$value$flags";
        }
        
        $options = getopt($short, $long);
        if (!$options)
        {
            throw new \Exception("Cannot process commandline arguments");
        }
        
        foreach ($this->pairs as $key => $value)
        {
            $match = null;
            if (isset($options[$key]) && !empty($options[$key]))
            {
                $match = $options[$key];
            }
            elseif (isset($options[$value]) && !empty($options[$value]))
            {
                $match = $options[$value];
            }
            $this->args[$key] = $match;
        }
    }
    
    public function getArg($short)
    {
        return isset($this->args[$short]) ? $this->args[$short] : null;
    }
}

?>
