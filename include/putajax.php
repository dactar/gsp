<?
        if ($_REQUEST[OBJECT] == "MAIN_BOX1")
        {
                $event = new event();
                $event->update_from_grid();
                exit;
        }
?>

