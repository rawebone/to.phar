<?php
namespace ToPhar;

/**
 * to.phar
 * =======
 *
 * Copyright (c) 2011 Nick Rawe
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @license http://www.opensource.org/licenses/MIT
 * @author Nick Rawe
 */

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
