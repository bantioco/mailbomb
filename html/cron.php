<div class="mailbomb_setting_container">

    <div>
        <div class="mailbomb_setting_title">Ajouter une nouvelle tache</div>

        <form method="POST">

            <span class="mailbomb_large_span">mailbomb_cron_event</span>

            <select name="mailbomb_cron_schedule" class="mailbomb_input_large">

                <option value="mailbomb_schedule_single_event">No-repeating</option>

                <?php if( $schedules && is_array( $schedules ) ): foreach( $schedules as $name => $schedule ): ?>

                    <option value="<?php echo $name;?>"><?php echo $schedule['display'];?></option>

                <?php endforeach; endif; ?>

            </select>

            <input type="hidden" name="mailbomb_cron_post" value="1">
            <input type="submit" name="submit" class="mailbomb-btn" value="Valider">

        </form>
        
    </div>

    <br><br>
    <!--
    <form method="POST">
        <table class="mailbomb_cron_table">
            <thead>
                <th class="mailbomb_cron_th">JOURS</th>
                <th class="mailbomb_cron_th">HEURES</th>
                <th class="mailbomb_cron_th">MINUTES</th>
                <th class="mailbomb_cron_th">MOIS</th>
                <th class="mailbomb_cron_th">TEMPLATE</th>
                <th class="mailbomb_cron_th"></th>
            </thead>

            <tbody>

                <tr>

                    <td class="mailbomb_cron_td">

                        <div>
                            <select name="mailbomb_cron_day" id="mailbomb_cron_day">
                                <option value="all_days">Tous les jours</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>
                    </td>

                    <td class="mailbomb_cron_td">
                        <div>
                            <select name="mailbomb_cron_hours" id="mailbomb_cron_hours">
                                <option value="all_hours">Toutes les heures</option>
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                            </select>
                        </div>
                    </td>

                    <td class="mailbomb_cron_td">
                        <div>
                            <select name="mailbomb_cron_minutes" id="mailbomb_cron_minutes">
                                <option value="all_minutes">Toutes les minutes</option>
                                <option value="00">00</option>
                                <option value="05">05</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="30">30</option>
                                <option value="35">35</option>
                                <option value="40">40</option>
                                <option value="45">45</option>
                                <option value="50">50</option>
                                <option value="55">55</option>
                                <option value="60">60</option>
                            </select>
                        </div>
                    </td>

                    <td class="mailbomb_cron_td">

                        <div>
                            <select name="mailbomb_cron_month" id="mailbomb_cron_month">
                                <option value="all_month">Tous les mois</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>

                            </select>
                        </div>

                    </td>

                    <td class="mailbomb_cron_td"><?php //echo $defaultTemplateNewsletter;?></td>

                    <td class="mailbomb_cron_td mailbomb_cron_td_center">
                        <input type="hidden" name="mailbomb_cron_post" value="1">
                        <button type="submit" name="submit" class="mailbomb-btn">VALIDER</button>
                    </td>

                </tr>

            </tbody>

        </table>

    </form>

    <br><br>
    -->

    <div>
        <div class="mailbomb_setting_title">Taches Planifiées</div>

        <table class="mailbomb_cron_table">
            <thead>
                <tr>
                    <!--<th class="mailbomb_cron_th mailbomb_cron_th_center"><input type="checkbox"></th>-->
                    <th class="mailbomb_cron_th">Nom</th>
                    <th class="mailbomb_cron_th">Recurence</th>
                    <th class="mailbomb_cron_th">Arguments</th>
                    <th class="mailbomb_cron_th">Interval</th>
                    <th class="mailbomb_cron_th">Recurence</th>
                    <th class="mailbomb_cron_th">Prochaine execution</th>
                    <th class="mailbomb_cron_th"></th>
                    <th class="mailbomb_cron_th"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if( $cronJobsGet && is_array( $cronJobsGet ) ): 
                        //echo "<pre>"; print_r($cronJobsGet); echo "</pre>";
                        //date_default_timezone_set( 'Europe/Paris' ); 
                        //echo "<div> Now : ".date('d-m-Y H:i:s')."</div>";

                        foreach( $cronJobsGet as $key => $crons ): if( $key != 'version' ): 
                            if( $crons && is_array( $crons ) ): foreach( $crons as $index => $cron): 
                ?>
                            <tr class="mailbomb_cron_tr">
                                <!--
                                <td class="mailbomb_cron_td mailbomb_cron_td_center">
                                    <?php //if( $index === "mailbomb_cron_event" ):?><input type="checkbox"><?php //endif;?>
                                </td>
                                -->

                                <td class="mailbomb_cron_td"><?php echo $index;?></td>

                                <td class="mailbomb_cron_td">
                                    <?php if( $cron && is_array( $cron ) ): foreach( $cron as $i => $t): echo $t['schedule']; endforeach; endif;?>
                                </td>

                                <td class="mailbomb_cron_td">
                                    <?php if( $cron && is_array( $cron ) ): foreach( $cron as $i => $t): if( is_array( $t['args'] ) ): foreach( $t['args'] as $k => $a): echo $a; endforeach; endif; endforeach; endif;?>
                                </td>

                                <td class="mailbomb_cron_td"><?php if( $cron && is_array( $cron ) ): foreach( $cron as $i => $t): if( isset( $t['interval'] ) ): echo $t['interval']; endif; endforeach; endif;?></td>
                            <td class="mailbomb_cron_td"><?php if( $cron && is_array( $cron ) ): foreach( $cron as $i => $t): if( isset( $t['interval'] ) ): echo /*gmdate("H:i:s", $t['interval']);*/ self::sec2Time( $t['interval'] ); /*self::convertSeconds( $t['interval'] );*/ endif; endforeach; endif;?></td>
                                <td class="mailbomb_cron_td"><?php if( $cron && is_array( $cron ) ): foreach( $cron as $i => $t): echo date( "d-M-Y H:i:s", $key ); endforeach; endif;?></td>

                                <td class="mailbomb_cron_td mailbomb_cron_td_center">
                                    <?php if( $index === "mailbomb_cron_event" ):?>
                                        <form method="POST">
                                            <input type="hidden" name="mailbomb_cron_timestamp_delete" value="<?php echo $key;?>">
                                            <input type="hidden" name="mailbomb_cron_name_delete" value="<?php echo $index;?>">
                                            <button type="submit" name="submit" class="mailbomb-btn">DELETE</button>
                                        </form>
                                    <?php endif;?>
                                </td>

                                <td class="mailbomb_cron_td mailbomb_cron_td_center">
                                    <?php if( $index === "mailbomb_cron_event" ):?>
                                        <form method="POST">
                                            <input type="hidden" name="mailbomb_cron_run_test" value="<?php echo $key;?>">
                                            <button type="submit" name="submit" class="mailbomb-btn">RUN</button>
                                        </form>
                                    <?php endif;?>
                                </td>

                            </tr>
                <?php 
                    endforeach;endif;
                    endif;endforeach;
                    endif;
                ?>
            </tbody>
        </table>

    </div>

    <br><br>

    <div>
        <div class="mailbomb_setting_title">Options de planification</div>

        <table class="mailbomb_cron_table">
            <thead>
                <th class="mailbomb_cron_th">name</th>
                <th class="mailbomb_cron_th">interval</th>
                <th class="mailbomb_cron_th">display</th>
            </thead>

            <tbody>

                <?php if( $schedules && is_array( $schedules ) ): foreach( $schedules as $name => $schedule ): ?>

                    <tr class="mailbomb_cron_tr">
                        <td class="mailbomb_cron_td"><?php echo $name;?></td>
                        <td class="mailbomb_cron_td"><?php echo $schedule['interval'];?></td>
                        <td class="mailbomb_cron_td"><?php echo $schedule['display'];?></td>
                    </tr>

                <?php endforeach; endif; ?>

            </tbody>

        </table>

    </div>

</div>