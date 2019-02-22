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
    setTimeout(function(){
      var email = null;
      var val = $(".FormControl[name=email]").val();
      if(val) {
        email = val.match(/ouFd\S+\@mozigu\.cn/g);
      }
      var submit = $(".Button.Button--primary.Button--block[type=submit]");
      if(email && submit.text() == '注册') {
        submit.click();
      }
    }, 800);
  });
});
