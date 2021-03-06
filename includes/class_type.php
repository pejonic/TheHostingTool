<?php
/* Copyright © 2014 TheHostingTool
 *
 * This file is part of TheHostingTool.
 *
 * TheHostingTool is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TheHostingTool is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TheHostingTool.  If not, see <http://www.gnu.org/licenses/>.
 */

//Check if called by script
if(THT != 1){die();}

//Create the class
class type {

    public $classes = array(); # All the classes here when createAll called

    # Start the functions #

    function __construct() {
        if($typesDir = opendir(LINK . "types")) {
            while(false !== ($entry = readdir($typesDir))) {
                if(!preg_match("/^(\w[\w\.]*)\.php$/", $entry, $matches)) {
                    continue;
                }
                $newType = $this->createType($matches[1]);
                $this->classes[$newType->getInternalName()] = $newType;
            }
        }
    }

    public function acpPadd($type) { # Returns the html of a custom form
        global $style;
        if(!$this->classes[$type]) {
            $type = $this->createType($type);
        }
        else {
            $type = $this->classes[$type];
        }
        if($type->acpForm) {
                        $html .= $style->javascript();
                        $html .= '<script type="text/javascript">
                        var gi = 0;
                        $(document).ready(function(){
                            //var info = new Array();
                            var info;
                            $("#submitIt").click(function() {
                                $("input").each(function(i) {
                                    if(gi == 0) {
                                        info = this.name + "="  + $("#" + this.id).val();
                                    }
                                    else {
                                        info = info + "," + this.name + "="  + $("#" + this.id).val();
                                    }


                                    gi++;
                                });
                                $("select").each(function(i) {
                                    if(gi == 0) {
                                        info = this.name + "="  + $("#" + this.id).val();
                                    }
                                    else {
                                        info = info + "," + this.name + "="  + $("#" + this.id).val();
                                    }
                                    gi++;
                                });
                                var id = window.name.toString().split("-")[1];
                                window.opener.transfer(id, info);
                                window.close();
                            });
                        });
                        </script>';

            foreach($type->acpForm as $key => $value) {
                $array['NAME'] = $value[0] .":";
                $array['FORM'] = $value[1];
                $html .= $style->replaceVar("tpl/acptypeform.tpl", $array);
            }
                        $html .= "<button id=\"submitIt\">Submit</button>";
            return $html;
        }
    }

    public function orderForm($type) { # Returns the html of a custom form
        global $style;
        if(!$this->classes[$type]) {
            $type = $this->createType($type);
        }
        else {
            $type = $this->classes[$type];
        }
        if($type->orderForm) {
            foreach($type->orderForm as $key => $value) {
                $array['NAME'] = $value[0] .":";
                $array['FORM'] = $value[1];
                $html .= $style->replaceVar("tpl/acptypeform.tpl", $array);
            }
            return $html;
        }
    }

    public function signupForm($type) { # Returns the html of a custom form
        global $style;
        if(!$this->classes[$type]) {
            $type = $this->createType($type);
        }
        else {
            $type = $this->classes[$type];
        }
        if($type->acpForm) {
            foreach($type->acpForm as $key => $value) {
                $array['NAME'] = $value[0] .":";
                $array['FORM'] = $value[1];
                $html .= $style->replaceVar("tpl/acptypeform.tpl", $array);
            }
            return $html;
        }
    }

    public function createType($type) { // Autoloads the type and returns it
        $class = "\TheHostingTool\Types\\$type";
        if(!class_exists($class)) {
            die("THT Fatal Error: No such type ($class).");
        }
        // Check for proper inheritance
        if(!in_array("TheHostingTool\Interfaces\Type", class_implements($class))) {
            die("THT Fatal Error: $class does not implement TheHostingTool\Interfaces\Type");
        }
        return new $class();
    }

    public function createAll() { // Creates all types and returns them
        xdebug_print_function_stack('$type->createAll called.');
        die();
    }

    public function determineType($id, $showErrors = true) { # Returns type of a package
        global $db;
        global $main;
        $query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($id)}'");
        if($db->num_rows($query) == 0) {
            if($showErrors) {
                $array['Error'] = "That package doesn't exist!";
                $array['Package ID'] = $id;
                $main->error($array);
            }
            return false;
        }
        $data = $db->fetch_array($query);
        return $data['type'];
    }
    public function determineServer($id, $showErrors = true) { # Returns server of a package
        global $db;
        global $main;
        $query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($id)}'");
        if($db->num_rows($query) == 0) {
            if($showErrors) {
                $array['Error'] = "That package doesn't exist!";
                $array['Package ID'] = $id;
                $main->error($array);
            }
            return false;
        }
        $data = $db->fetch_array($query);
        return $data['server'];
    }
    public function determineServerType($id, $showErrors = true) { # Returns server of a package
        global $db;
        global $main;
        $query = $db->query("SELECT * FROM `<PRE>servers` WHERE `id` = '{$db->strip($id)}'");
        if($db->num_rows($query) == 0) {
            if($showErrors) {
                $array['Error'] = "That server doesn't exist!";
                $array['Server ID'] = $id;
                $main->error($array);
            }
            return false;
        }
        $data = $db->fetch_array($query);
        return $data['type'];
    }
    public function determineBackend($id, $showErrors = true) { // Returns server of a package
        global $db;
        global $main;
        $query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($id)}'");
        if($db->num_rows($query) == 0) {
            if($showErrors) {
                $array['Error'] = "That package doesn't exist!";
                $array['Package ID'] = $id;
                $main->error($array);
            }
            return false;
        }
        $data = $db->fetch_array($query);
        return $data['backend'];
    }

    public function acpPedit($type, $values) { // Returns the type's acpForm[] content
        global $style;
        if(!$this->classes[$type]) {
            $type = $this->createType($type);
        }
        else {
            $type = $this->classes[$type];
        }
        if($type->acpForm) {
            // Oh this just hurts...
            if(method_exists($type, 'repopFormHack')) {
                $type->repopFormHack($values['forum']);
            }
            foreach($type->acpForm as $value) {
                $array['NAME'] = $value[0] .":";
                $hit = explode("/>", $value[1]); // haha... $hit
                $default = "";                   // There is nothing funny about this code :(
                if(stripos($value[1], "</select>") === false) {
                    $default = ' value="'.$values[$value[2]].'" />';
                }
                $array['FORM'] = $hit[0]. $default;
                $html .= $style->replaceVar("tpl/acptypeform.tpl", $array);
            }
            return $html;
        }
    }

    public function additional($id) { // Returns the additonal values on a package
        global $db;
        $query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($id)}'");
        $data = $db->fetch_array($query);
        $content = explode(",", $data['additional']);
        foreach($content as $key => $value) {
            $inside = explode("=", $value);
            $values[$inside[0]] = $inside[1];
        }
        return $values;
    }

    public function userAdditional($id) { // Returns the additional info of a PID
        global $db, $main;
        $query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}'");
        if($db->num_rows($query) == 0) {
            $array['Error'] = "That user pack doesn't exist!";
            $array['PID'] = $id;
            $main->error($array);
            return;
        }
        else {
            $query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}'");
            $data = $db->fetch_array($query);
            $content = explode(",", $data['additional']);
            foreach($content as $key => $value) {
                $inside = explode("=", $value);
                $values[$inside[0]] = $inside[1];
            }
            return $values;
        }
    }
}
//End Type
