import { extend } from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';

app.initializers.add('zengkv-auth-momo', () => {
  extend(LogInButtons.prototype, 'items', function(items) {
    items.add('momo',
      <LogInButton
        className="Button LogInButton--momo"
        icon="fab fa-momo"
        path="/auth/momo">
        {app.translator.trans('zengkv-auth-momo.forum.log_in.with_momo_button')}
      </LogInButton>
    );
  });
});
