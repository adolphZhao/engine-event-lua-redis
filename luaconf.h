#include <lua.h>
#include <lauxlib.h>
#include <lualib.h>
#include <string.h>
#include <errno.h>
#include <dirent.h>
#include <stdlib.h>
#include "parson.h"
#include "hashtable.h"

#define MAX_LUA_BUFFER 4096 

struct lua_ptr{
        char *name;
	lua_State * L;
};

typedef struct lua_ptr lua_ptr_entry ;

void fread_lua(char *file,lua_ptr_entry *lpe);

int jsonParse(lua_State *L);

void init_lua_buffer();

lua_State * new_state(char * file);
