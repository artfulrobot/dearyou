# Dear You

This extension provides a couple of tokens that you may want to use in your
emails, for example.

For example: you might want it to say "Dear Billy," (using nickname), but if
there's no nickname, "Dear William," (using first name) and if there's no first
name, simply "Hi,".

There are settings for individuals, organisations and households, and a set of
these for 'informal' and one for 'formal'.

## Consider alternatives before installing.

- [Fancy Tokens](https://civicrm.org/extensions/fancy-tokens)

- Use Smarty Conditionals in your Greeting Settings, e.g.:
       {capture assign=first_name}{contact.first_name}{/capture}Dear {$first_name|default:Supporter},


The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.0+
* CiviCRM 5.10+

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl dearyou@https://github.com/artfulrobot/dearyou/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/artfulrobot/dearyou.git
cv en dearyou
```

## Usage

Config is found in the <em>Administer</em> » <em>Dear You greetings</em>.
