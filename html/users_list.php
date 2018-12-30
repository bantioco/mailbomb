<div>
    <div class="mailbomb_users_total">
        <span>[ Nombre total d'utilisateurs : <?php echo $totalUsers;?> ] </span> - 
        <span>[ Nombre total de pages : <?php echo $numberPages;?> ] </span>
        <span>[ Page actuelle : <?php echo $page;?> ] </span>
    </div>

    <div class="mailbomb_users_page_number">

        <form method="POST">
            Items par page : <input type="number" name="users_list_items_per_page" min="1" max="100" value="<?php echo $numberItems;?>"> 
            <input type="hidden" name="users_list_items_per_page_id" value="<?php echo $itemsPerPageId;?>">
            <input id="users_list_items_per_page_submit" type="submit" name="submit" class="mailbomb-btn" value="Valider">
        </form>

    </div>
    <form method="POST">

        <div class="mailbomb_users_list_delete_btn">
            <button disabled="disabled" class="mailbomb-btn mailbomb-btn-red" name="users_list_selected_delete" type="submit">Supprimer</button>
        </div>

        <table class="mailbomb_users_table">
            <thead>
                <th class="mailbomb_users_th mailbomb_users_th_center"><input type="checkbox" name="users_list_selected_all" id="users_list_selected_all"></th>
                <th class="mailbomb_users_th mailbomb_users_th_center">ID</th>
                <th class="mailbomb_users_th">E-mail</th>
                <th class="mailbomb_users_th">Newsletter reçue</th>
                <th class="mailbomb_users_th">Date d'inscription</th>
            </thead>
            <tbody>

                <?php if( $userLists ): foreach( $userLists as $userList ): ?>
                
                    <tr class="mailbomb_users_tr">

                        <td class="mailbomb_users_td mailbomb_users_td_center">
                            <input type="checkbox" name="user_list_selected_delete[]" value="<?php echo $userList->id; ?>">
                        </td>
                        <td class="mailbomb_users_td mailbomb_users_td_center"><div><?php echo $userList->id; ?></div></td>
                        <td class="mailbomb_users_td"><div><?php echo $userList->email; ?></div></td>
                        <td class="mailbomb_users_td"><div><?php echo $userList->newsletter_sending; ?></div></td>
                        <td class="mailbomb_users_td"><div><?php echo $userList->created_at; ?></div></td>

                    </tr>

                <?php endforeach; endif; ?>

            </tbody>
        </table>
        <input type="hidden" name="mailbomb_users_list_delete" value="1">
    </form>
</div>
<div class="mailbomb_pagination_container">

    <div class="mailbomb_pagination_flex">

        <?php 
            $prev           = (int)$page - 1;

            $displayPrev    = "";

            if( $prev < 1 ) $displayPrev = "display:none;";

            $prevLink       = admin_url( 'admin.php?page=mailbomb-setting&users-list=true&number-page='.$prev.'#mailbomb-users-list' );

            $displayBefore  = "";

            if( ( (int)$page - 3 ) <= 1 ) $displayBefore = "display:none;";
        ?>

        <a style="<?php echo $displayPrev;?>margin-right: 20px;" href="<?php echo $prevLink;?>"><div class="mailbomb_pagination_flex_item">PREV</div></a>

        <div style="<?php echo $displayBefore;?>border:0;" class="mailbomb_pagination_flex_item">...</div>

        <?php 

            for ($i = 1; $i <= $numberPages; $i++): 

                $link       = admin_url( 'admin.php?page=mailbomb-setting&users-list=true&number-page='.$i.'#mailbomb-users-list' );

                $current    = false;

                if( (int)$page === $i ) $current = true;

                $displayItem = "";

                if( $i < ( (int)$page - 3 )  ) $displayItem = "display:none;";

                if( $i > ( (int)$page + 3 ) ) $displayItem = "display:none;";

                if( $i <= 7 && (int)$page < 3 ) $displayItem = "";
        ?>
            
        <?php if( !$current ) : ?>

            <a style="<?php echo $displayItem;?>" href="<?php echo $link;?>"><div class="mailbomb_pagination_flex_item"><?php echo $i;?></div></a>

        <?php else: ?>

            <div class="mailbomb_pagination_flex_item mailbomb_pagination_flex_current"><?php echo $i;?></div>

        <?php endif;?>

        <?php endfor;

            $next           = (int)$page + 1;

            $displayNext    = "";

            if( $next > (int)$numberPages ) $displayNext = "display:none;";

            $nextLink   = admin_url( 'admin.php?page=mailbomb-setting&users-list=true&number-page='.$next.'#mailbomb-users-list' );

            $displayAfter  = "";

            if( ( (int)$page + 3 ) >= (int)$numberPages ) $displayAfter = "display:none;";
        ?>

        <div style="<?php echo $displayAfter;?>border:0;" class="mailbomb_pagination_flex_item">...</div>

        <a style="<?php echo $displayNext;?>margin-left: 20px;" href="<?php echo $nextLink;?>"><div class="mailbomb_pagination_flex_item">NEXT</div></a>

    </div>

</div>