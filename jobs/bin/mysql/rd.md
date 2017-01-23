#每个星期日凌晨3:00执行完全备份脚本
0 3 * * 0 /root/full_backup.sh >/dev/null 2>&1
#周一到周六凌晨3:00做增量备份
0 3 * * 1-6 /root/inc_backup.sh >/dev/null 2>&1