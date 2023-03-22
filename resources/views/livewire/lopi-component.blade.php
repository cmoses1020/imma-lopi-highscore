<div class="relative flex justify-center items-center text-center h-screen max-h-screen overflow-hidden" x-data="lopi()" x-ref="main">
    <div>
        <img class="mx-auto w-[200px]" src="{{ Vite::asset('resources/lopi_assets/lopibig.png') }}"></img>
        <h1 class="mt-[10px] mb-[5px] text-4xl font-bold">Enjoy some Lopi! <span class="text-[#561378] text-sm">(actually Jim)</span></h1>
        
        <div x-text="count" class="text-[#444] text-[24px] font-bold mt-[10px] font-['Trebuchet_MS']"></div>
    
        <button
            @keydown.enter.prevent="null"
            @keydown.space.prevent="null"
            x-on:click="lopiPopup"
            class="block w-full text-[15px] bg-[#ffadd2] text-white font-bold py-[10px] px-[20px] border border-white hover:bg-opacity-80 hover:shadow-[#ffadd2] active:shadow-[#ffadd2] hover:shadow-md active:shadow-2xl active:bg-white active:text-[#ffadd2] active:border-[#ffadd2]"
        >I'm Lopi</button>
        
        <div class="m-[20px] text-[16px] text-black font-bold group">
            <a href="https://www.youtube.com/@Punkalopi" target="_blank">
                Punkalopi's Youtube Channel
                <span class="text-[#561378] text-sm group-hover:text-purple-500/80">(check it out, it's cool!)</span>
            </a>
        </div>
        <div class="m-[20px] text-[16px] text-black font-bold group">
            <a href="https://twitter.com/Punkalopi" target="_blank">
                Punkalopi's Twitter
                <span class="text-[#561378] text-sm group-hover:text-purple-500/80">(silly bird app, very fun!)</span>
            </a>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        let lopi = () => {
            return {
                sounds: [
                    new Audio("{{ Vite::asset('resources/lopi_assets/1.mp3') }}"),
                    new Audio("{{ Vite::asset('resources/lopi_assets/2.mp3') }}"),
                    new Audio("{{ Vite::asset('resources/lopi_assets/3.mp3') }}"),
                ],
                lopiImage: "{{ Vite::asset('resources/lopi_assets/lopi.png') }}",
                count: 0,
                lopiPopup() {
                    this.sounds[Math.floor(Math.random()*this.sounds.length)].play()
                    this.count++

                    let lopiImage = document.createElement('img')
                    lopiImage.src = this.lopiImage
                    lopiImage.style.position = 'absolute'
                    lopiImage.style.top = Math.floor(Math.random() * (window.innerHeight - 200)) + 'px'
                    lopiImage.style.left = Math.floor(Math.random() * (window.innerWidth - 200)) + 'px'
                    lopiImage.style.width = '250px'
                    lopiImage.style.height = 'auto'
                    lopiImage.style.zIndex = '9999'
                    lopiImage.style.transition = 'all 0.5s ease-in-out'
                    lopiImage.style.opacity = '0'
                    lopiImage.style.transform = 'scale(0.5)'
                    lopiImage.style.pointerEvents = 'none'
                    this.$refs.main.appendChild(lopiImage)
                    setTimeout(() => {
                        lopiImage.style.opacity = '1'
                        lopiImage.style.transform = 'scale(1)'
                    }, 100)
                    setTimeout(() => {
                        lopiImage.style.opacity = '0'
                        lopiImage.style.transform = 'scale(0.5)'
                    }, 750)
                    setTimeout(() => {
                        lopiImage.remove()
                    }, 1000)
                }
            }
        }
    </script>
@endPushOnce