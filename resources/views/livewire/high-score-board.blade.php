<div wire:ignore>
    <div class="text-3xl mb-2 text-center">
        High Scores
    </div>

    <div class="space-y-2 relative" x-data="scoreBoard">
        <template x-for="(user, index) in users" x-bind:key="index">
            <div class="flex w-full justify-between absolute transition-all top-0 duration-[1000ms] ease-in-out"
                :style="{top: user.position.top + 'px', opacity: user.opacity}"
            >
                <div x-text="user.rank_with_ordinal"></div>
                <div x-text="user.name"></div>
                <div x-text="user.click_count"></div>
            </div>
        </template>
    </div>
</div>


@pushOnce('scripts')
    <script>
        let scoreBoard = () => {
            return {
                init() {
                    //set interval to get users every 1 seconds
                    this.updateUsers()
                    setInterval(() => {
                        this.updateUsers()
                    }, 1000)
                },
                users: [],
                rank: null,
                totalClicks: 0,
                updateUsers() {
                    this.$wire.getUsers().then((data) => {
                        data.forEach((user, index) => {
                            // if this.users already contains the user update it, else create a new one
                            let userIndex = this.users.findIndex((u) => u.id == user.id)
                            if (userIndex > -1) {
                                this.users[userIndex].new.rank = user.user_rank
                                this.users[userIndex].new.click_count = user.click_count
                                this.users[userIndex].new.rank_with_ordinal = user.rank_with_ordinal
                            } else {
                                this.users.push(new User(user))
                            }
                        })
                        // if user is in the old data and not the new data mark if for deleteion
                        this.users.forEach((user) => {
                            if (data.findIndex((u) => u.id == user.id) == -1) {
                                user.markForDeletion()
                            }
                        })
                    })
                        this.sortUsers()
                        this.users.forEach((user) => {
                            user.setPoisition()
                        })
                        this.deleteUsers()
                },
                sortUsers() {
                    this.users.slice()
                        .filter((user) => user.markForDeletionAt == null)
                        .sort((a, b) => a.new.rank - b.new.rank)
                        .forEach((user, index) => {user.index = index})
                },
                deleteUsers() {
                    this.users = this.users.filter((user) => {
                        return user.markForDeletionAt == null || user.markForDeletionAt > Date.now()
                    })
                }
            }
        }

        class User {
            constructor(user, element) {
                this.id = user.id
                this.rank = user.user_rank
                this.name = user.name
                this.click_count = user.click_count
                this.rank_with_ordinal = user.rank_with_ordinal
                this.position = { top: 300 }
                this.opacity = 0
                this.markForDeletionAt = null
                this.index = null
                this.new = {
                    rank: user.user_rank,
                    click_count: user.click_count,
                    rank_with_ordinal: user.rank_with_ordinal
                }
            }

            setPoisition() {
                if (this.markForDeletionAt != null) {
                    this.position.top = 300
                    this.opacity = 0
                } else {
                    this.opacity = 1
                    this.position.top = this.index * 30
                }
                this.rank = this.new.rank
                this.rank_with_ordinal = this.new.rank_with_ordinal
                this.countUp()
            }
            countUp() {
                let interval = setInterval(() => {
                    if (this.click_count < this.new.click_count) {
                        this.click_count++
                    } else {
                        clearInterval(interval)
                    }
                }, 1000 / 60)
            }

            markForDeletion() {
                this.markForDeletionAt = Date.now() + 1500
            }
        }
    </script>
@endPushOnce