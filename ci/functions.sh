#!/usr/bin/env bash
#===================================================================================
#
#         FILE: functions.sh
#
#  DESCRIPTION: Defines a set of functions useful when building
#               and deploying applications.
#
#      OPTIONS: ...
# REQUIREMENTS: ...
#         BUGS: ---
#        NOTES: ---
#       AUTHOR: Martin Pettersson
#      COMPANY: Gota Media AB
#      VERSION: 0.1.0
#      CREATED: 2019-02-14
#===================================================================================

#===  FUNCTION  ====================================================================
#        NAME: get-build-environment
# DESCRIPTION: Returns a normalized name of the current environment: dev|test|prod
#===================================================================================
function get-build-environment()
{
    local test_branch=release/

    if [ -n "${BITBUCKET_TAG}" ]
    then
        echo prod

        return 0
    fi

    if [ "${BITBUCKET_BRANCH:0:${#test_branch}}" = "${test_branch}" ]
    then
        echo test

        return 0
    fi

    echo dev
}

#===  FUNCTION  ====================================================================
#        NAME: resolve-variable
# DESCRIPTION: ---
#===================================================================================
function resolve-variable()
{
    local variable=$(get-build-environment)_${1}
    local value=

    if [ $(eval echo \${${variable}+is_set}) 2> /dev/null = is_set ]
    then
        value=$(eval echo \$${variable})
    else
        value=$(eval echo \$${1})
    fi

    echo ${value:-${2}}
}

#===  FUNCTION  ====================================================================
#        NAME: assert-variables
# DESCRIPTION: ---
#===================================================================================
function assert-variables()
{
    local missing_parameters=()

    for variable in ${@}
    do
        eval export ${variable}='$(resolve-variable ${variable})'

        if [ -z "$(eval echo \$${variable})" ]
        then
            missing_parameters+=(${variable})
        fi
    done

    if [ ${#missing_parameters[@]} -ne 0 ]
    then
        >&2 echo "Missing parameters: ${missing_parameters[*]}"

        exit 1
    fi
}

#===  FUNCTION  ====================================================================
#        NAME: interpolate
# DESCRIPTION: ---
#===================================================================================
function interpolate()
{
    local prepared=$(sed -e 's|"|\\"|g' -e 's|{{\([^}]*\)}}|\${\1}|g' < ${1})

    eval echo ${prepared}
}
