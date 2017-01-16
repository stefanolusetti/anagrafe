<div id="searchbar">
    <?php 
    echo form_open('admin/login') ?>
        <div class="fieldarea">
            <?php f_text('admin_user', 'User'); ?>
            <?php f_password('admin_pwd', 'Password'); ?>
            <div class="field">
                <label for='submit'>&nbsp</label>
                <?php echo form_submit('submit', 'login'); ?>
             </div>
        </div>
        <?php if($msg): ?>
            <div class="errors">
                <p>
                    <?php echo $msg; ?>
                </p>
            </div>
        <?php endif; ?>
    </form>
</div>
