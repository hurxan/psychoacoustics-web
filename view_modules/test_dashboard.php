
<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");
?>

<div class="container">

    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-4 bg-light p-5 rounded-4 border mt-5" id="StartingWindow">
            <h2 class="text-center mb-5">Ready?</h2>
            <div class="d-grid">
                <button type="button" class="btn btn-lg btn-success btn-block" id="start" onclick="start()">
                    Let's start!
                </button>
            </div>
        </div>

        <div class="col-12 col-md-6 bg-light p-5 rounded-4 border mt-5" id="PlayForm" style="display: none">
            <div class="row gy-3 justify-content-between align-items-center">
                <h2 class="col-12 text-center mb-3" id="questionmessage"><?php echo $testMsg ?></h2>
                <?php
                $colors = ["#198754", "#dc3545", "#0d6efd", "#e0b000", "#a000a0", "#ff8010", "#50a0f0", "#703000", "#606090"];
                for ($i = 1; $i <= intval($testParam['nAFC']); $i++) { ?>
                    <div class="col-12 col-sm-4 d-grid">
                        <?php echo "<button type='button' class='btn btn-lg btn-success' style='background-color:" .
                            $colors[($i - 1) % count($colors)] . "; border-color: " . $colors[($i - 1) % count($colors)] . ";' id='button{$i}' onclick = 'computeResponse({$i})' disabled>{$i}° sound</button>"; ?>
                    </div>
                <?php }
                ?>
            </div>
        </div>
    </div>


    <div class="row justify-content-center align-items-center">
        <div class='col-12 col-md-6 alert alert-danger mt-5' id="wrong" style="display: none">Wrong!</div>
        <div class='col-12 col-md-6 alert alert-success mt-5' id="correct" style="display: none">Correct!</div>
    </div>

</div>