<?
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @                                     GSP Global Support Platform                                       @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @   Name  : GSP Code Loader       Initiale Release : 1.0   30-06-2006    Author : Jean-Claude Schopfer  @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @   Changes : Version  | When       | Who  |  What                                                      @
// @             ---------------------------------------------------------------------------------------   @
// @                      |            |      |                                                            @
// @                      |            |      |                                                            @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @  GSP Code Loader is written in PHP. This programm load all php code in $dir directory. $dir have to   @
// @  be set before executing this code.
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
?>
<?
   
// Put all filenames included in $dir into $files variable

$files = scandir("include/" . $dir);
foreach($files as $filename)
{
	// Loading any file ended by .php

	$extfilename=preg_split("/\./",$filename);

	if ($extfilename[1] == "php")
	{
		require_once("include/" . $dir . "/" . $filename);
	}
}

?>
