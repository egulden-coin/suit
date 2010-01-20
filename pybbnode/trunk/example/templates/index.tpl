[trim]
[code]code/index.py[/code]
[code]code/header.py[/code]
[execute][template]templates/header.tpl[/template][/execute]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
        [if condition="[var json='true']message[/var]"]
        <fieldset>
            <legend>[var]language=>message[/var]</legend>
            [var]executed[/var]
        </fieldset>
        [/if]
        <form action="#" method="post">
        <p>[var]language=>message[/var]: <textarea name="message" style="width: 100%;" rows="20">
[var]message[/var]</textarea></p>
        <p><input type="submit" name="submit" value="[var]language=>submit[/var]" /></p>
        </form>
    </div>
[execute][template]templates/footer.tpl[/template][/execute]
[/trim]