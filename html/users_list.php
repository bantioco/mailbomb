<div>
    <table class="mailbomb_users_table">
        <thead>
            <th class="mailbomb_users_th">ID</th>
            <th class="mailbomb_users_th">Email</th>
            <th class="mailbomb_users_th">Created at</th>
            <th class="mailbomb_users_th">Delete</th>
        </thead>
        <tbody>

            <?php if( $userListsAll ): foreach( $userListsAll as $userList ): ?>
            
                <tr class="mailbomb_users_tr">

                    <td class="mailbomb_users_td"><div><?php echo $userList->id; ?></div></td>
                    <td class="mailbomb_users_td"><div><?php echo $userList->email; ?></div></td>
                    <td class="mailbomb_users_td"><div><?php echo $userList->created_at; ?></div></td>
                    <td class="mailbomb_users_td"><div>X</div></td>

                </tr>

            <?php endforeach; endif; ?>

        </tbody>
    </table>
</div>
<div>
    <?php echo $numberPages;?>
</div>