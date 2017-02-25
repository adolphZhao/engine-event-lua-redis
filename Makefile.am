AUTOMAKE_OPTIONS=foreign
server_CFLAGS=-D__WITH_MURMUR
server_LDFLAGS= -levent -llua -D__WITH_MURMUR
bin_PROGRAMS=server 
server_SOURCES=server.c parson.c luaconf.c hashtable.c murmur.c 
