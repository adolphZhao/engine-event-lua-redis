#                                               -*- Autoconf -*-
# Process this file with autoconf to produce a configure script.

AC_PREREQ([2.69])
AC_INIT(event_lua_server, 1.0, 1548104097@qq.com)
AM_INIT_AUTOMAKE(server,1.0)
AC_CONFIG_SRCDIR([config.h.in])
AC_CONFIG_HEADERS([config.h])

# Checks for programs.
AC_PROG_CC
AC_PROG_CXX

# Checks for libraries.
# FIXME: Replace `main' with a function in `-levent':
AC_CHECK_LIB([event], [main])
# FIXME: Replace `main' with a function in `-llua':
AC_CHECK_LIB([lua], [main])

# Checks for header files.
AC_CHECK_HEADERS([arpa/inet.h fcntl.h netinet/in.h stddef.h stdlib.h string.h sys/socket.h unistd.h])

# Checks for typedefs, structures, and compiler characteristics.
AC_TYPE_SIZE_T

# Checks for library functions.
AC_FUNC_MALLOC
AC_FUNC_STRTOD
AC_CHECK_FUNCS([memset socket strchr strerror strstr])

AC_CONFIG_FILES([Makefile])
AC_OUTPUT
