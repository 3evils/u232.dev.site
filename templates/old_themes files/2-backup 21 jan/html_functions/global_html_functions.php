<?php
///// global, table tr, td, and div classes for blocks
function begin_main_div()
{
    return "<div class='card'>";
}
function begin_head_div()
{
    return "<div class='card-divider'>";
}
function end_head_div()
{
    return "</div>";
}
function begin_head_label($x)
{
    return "<label for='checkbox_4' class='text-left'>";
}
function end_head_label()
{
    return "</label>";
}
function begin_body_div($x)
{
    return "<div class='card-section'>";
}
function end_body_div()
{
    return "</div>";
}
function end_div()
{
    return "</div>";
}
function begin_main_table()
{
    return "<table class='table hover'>"; 
}
/// end golbal global, table tr, td, and div classes for blocks

?>