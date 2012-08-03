<?php
if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }
?>

            <div class="output">
                <section class="output">
                    <h3>Output</h3>
                    <?php
                        $finalRequest = '';
                        foreach (getImageKeys() as $key => $value) {
                            $finalRequest .= '&' . $key . '=' . urlencode($value);
                        }
                        if (strlen($finalRequest) > 0) {
                            $finalRequest[0] = '?';
                        }
                    ?>
                    <div id="imageOutput">
                        <?php if ($imageKeys['text'] !== '') { ?><img src="image.php<?php echo $finalRequest; ?>" alt="Barcode Image" /><?php }
                        else { ?>Fill the form to generate a barcode.<?php } ?>
                    </div>
                </section>
            </div>
        </form>

        <div class="footer">
            <footer>
            All Rights Reserved &copy; <?php echo date('Y'); ?> <a href="http://www.barcodephp.com" target="_blank">Barcode Generator</a> PHP5-v<?php echo constant('VERSION'); ?>
            </footer>
        </div>
    </body>
</html>