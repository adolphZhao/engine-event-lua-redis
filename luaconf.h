#include <lua.h>
#include <lauxlib.h>
#include <lualib.h>
#include <string.h>
#include <errno.h>
#include <dirent.h>
#include <stdlib.h>
#include "parson.h"
#include "hashtable.h"

struct lua_ptr{
        char *name;
        lua_State * LS;
        struct lua_ptr * next;
};

typedef struct lua_ptr lua_ptr_entry ;

static int jsonParse(lua_State *L);

lua_State * createLS(char *file);

void iniLuaStates();

lua_State * findState(const char *file);

