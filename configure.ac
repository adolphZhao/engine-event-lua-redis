#                                               -*- Autoconf -*-
# Process this file with autoconf to produce a configure script.

AC_PREREQ([2.69])
AC_INIT(server, 1.1, 1548104097@qq.com)
AM_INIT_AUTOMAKE
AC_CONFIG_SRCDIR([config.h.in])
AC_CONFIG_HEADERS([config.h])
# Checks for programs.
AC_PROG_CC

# Checks for libraries.
AC_CHECK_LIB([event],[event_dispatch],[],[
        echo "Error! You need to have libevent installed!"
        exit -1
        ])
AC_CHECK_LIB([lua],[luaL_newstate],[],[
        echo "Error! You need to have liblua installed!"
        exit -1
])

# Checks for header files.
AC_CHECK_HEADERS([arpa/inet.h fcntl.h netinet/in.h stddef.h stdint.h stdlib.h string.h sys/socket.h unistd.h])

# Checks for typedefs, structures, and compiler characteristics.
AC_TYPE_INT8_T
AC_TYPE_SIZE_T
AC_CHECK_HEADER_STDBOOL
AC_TYPE_UINT32_T
AC_TYPE_UINT64_T
AC_TYPE_UINT8_T

# Checks for library functions.
AC_FUNC_MALLOC
AC_FUNC_STRTOD
AC_CHECK_FUNCS([memset socket strchr strerror strstr])

AC_CONFIG_FILES([Makefile])
AC_OUTPUT
