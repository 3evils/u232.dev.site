#include "user.h"

user::user(userid_t uid, bool leech, bool protect, bool freeswitch) : id(uid), deleted(false), leechstatus(leech), protect_ip(protect), free_switch(freeswitch) {
	stats.leeching = 0;
	stats.seeding = 0;
}
