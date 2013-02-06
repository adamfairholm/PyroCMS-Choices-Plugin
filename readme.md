# PyroCMS Choices Plugin

This is a simple plugin that allows you to loop through the choices that have been set up in a choices field type.

## Installation

Copy the choices.php file to your addons/shared\_addons/plugins or addons/site\_ref/plugins folder. It is now ready to use!

## Usage

You can loop through the key/value pairs for your choice data type using the cycle function:

    {{ choices:cycle field_slug="my_field_slug" field_namespace="my_field_namespace" }}
        {{ key }} {{ value }}
    {{ /choices:cycle }}

Simply provie the field slug and namespace. If you have only provided a value (instead of a key : value pair) both {{ key }} and {{ value }} will be the same value.