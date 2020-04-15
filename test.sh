#! /usr/bin/env bash

function usage {
  echo "Usage: $0 [-p path/to/php] [-g path/to/phpdbg] [-c path/to/composer] [-h]" 1>&2
  exit 1
}

SOURCE_PATH="KairosProject/"
PHP_PATH="/usr/bin/env php"
PHPDBG_PATH="/usr/bin/env phpdbg"
COMPOSER_PATH="/usr/bin/env composer"

while getopts "up:c:h:" opt; do
  case $opt in
    p)
      PHP_PATH=$OPTARG
      ;;
    g)
      PHPDBG_PATH=$OPTARG
      ;;
    c)
      COMPOSER_PATH=$OPTARG
      ;;
    h)
      usage
      exit 0
      ;;
    u)
      TEST_SUITE=" --testsuite unit "
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      usage
      exit 1
      ;;
    :)
      echo "Option -$OPTARG requires an argument." >&2
      usage
      exit 1
      ;;
  esac
done

STATUS=0
TEST_RES=""

function runner {
    TEST_RES=`$1`
    local TEST_RET=$?
}

function do_test {
	echo -e "\e[43m\e[30mRunning $1\e[0m\n\e[49m"
    TEST_RES=`$1`
    local TEST_RET=$?

    if [[ ${TEST_RET} != 0 ]]
    then
        echo -e "\e[31m$2 FAILED\e[0m"

        echo "$TEST_RES"

        STATUS=$((STATUS + $3))
    else
        echo -e "\e[32m$2 SUCCESS\e[0m"
    fi
}

do_test "$COMPOSER_PATH install" INSTALL 100
echo "$TEST_RES" >> build/composer.txt

runner "$PHP_PATH vendor/bin/phpcbf --standard=./csruleset.xml $SOURCE_PATH"
echo "$TEST_RES" >> build/phpcbf.txt

do_test "$PHP_PATH vendor/bin/phpcs --standard=./csruleset.xml $SOURCE_PATH" PHPCS 100
echo "$TEST_RES" > build/phpcs.txt

do_test "$COMPOSER_PATH validate" COMPOSER 100
echo "$TEST_RES" > build/composer.txt

do_test "$PHP_PATH vendor/bin/phpmd $SOURCE_PATH text ./phpmd.xml" PHPMD 100
echo "$TEST_RES" > build/phpmd.txt

do_test "$PHP_PATH vendor/bin/phpunit" PHPUNIT 100
echo "$TEST_RES" > build/phpunit.txt

do_test "$PHP_PATH vendor/bin/phpcpd $SOURCE_PATH" PHPCPD 1
echo "$TEST_RES" > build/phpcpd.txt

if [[ "$STATUS" -eq 0 ]]
then
    echo -e "\n\e[42m"
    echo -e "\e[30mTHE STATUS IS STABLE\n\e[0m\n\e[49m"
elif [[ "$STATUS" -lt 100 ]]
then
    echo -e "\n\e[43m"
    echo -e "\e[30mTHE STATUS IS UNSTABLE\n\e[0m\n\e[49m"
else
    echo -e "\n\e[41m"
    echo -e "\e[30mTHE STATUS IS FAILURE\n\e[0m\n\e[49m"
fi

exit ${STATUS}
