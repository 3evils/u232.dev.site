#ifndef USER_H
#define USER_H

#include <atomic>
#include "ocelot.h"

class user {
	private:
		userid_t id;
		bool deleted;
		bool leechstatus;
		bool protect_ip;
		bool free_switch;
		struct {
			std::atomic<uint32_t> leeching;
			std::atomic<uint32_t> seeding;
		} stats;
	public:
		user(userid_t uid, bool leech, bool protect, bool freeswitch);
		userid_t get_id() { return id; }
		bool get_free_switch() { return free_switch; }
		void set_free_switch(bool freeswitch) { free_switch = freeswitch; }
		bool is_deleted() { return deleted; }
		void set_deleted(bool status) { deleted = status; }
		bool is_protected() { return protect_ip; }
		void set_protected(bool status) { protect_ip = status; }
		bool can_leech() { return leechstatus; }
		void set_leechstatus(bool status) { leechstatus = status; }
		void decr_leeching() { --stats.leeching; }
		void decr_seeding() { --stats.seeding; }
		void incr_leeching() { ++stats.leeching; }
		void incr_seeding() { ++stats.seeding; }
		uint32_t get_leeching() { return stats.leeching; }
		uint32_t get_seeding() { return stats.seeding; }
};
#endif
