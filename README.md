# Grumphp slack reporter

This package can be used if you are using GrumPHP as your code qualiy tool and Slack as your communication platform.


GrumPHP is awesome, but since you are not allowed to commit crappy code, 
it is hard to see with which part of the quality checks your team is 
having trouble with.

This package will make it possible to visualize what your team members are doing wrong.
It can be used to reevaluate coding styles, configure new rules in your editor, 
or explain how the team members should test their code.

It is not intended to use this tool to blame people for writing bad code,
 but for helping improve their coding skills and push them to a higher level.

An example message looks like this:

<img src="https://raw.githubusercontent.com/phpro/grumphp-slack-reporter/master/resources/images/integration-sample.png"/>

## Installation

```sh
composer require --dev phpro/grumphp-slack-reporter
```

## Configuration

Add following lines to your `grumphp.yml` file:

```yaml
# grumphp.yml
parameters:
    extensions:
      - 'GrumPHP\Extension\Slack\ExtensionLoader'
    slack_reporter:
      webhook: 'https://hooks.slack.com/services/TEAMID/RANDOM1/RANDOM2'
      channel: '#yourchannel'
```

**webhook**

*Default: ''*

Configure an incoming webhook in Slack and paste the URL in this parameter.

**channel**

*Default: ''*

Which channel do you want to post the messages to?


 