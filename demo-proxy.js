/**
 * Proxy local para la demo con Cloudflare Tunnel.
 *
 * Expone UN solo puerto (8088) que enruta:
 *   - Peticiones HTTP        -> Laravel   (127.0.0.1:8000)
 *   - upgraded WebSocket     -> Reverb    (127.0.0.1:8080)
 *
 * Así, la sala de chat y los WebSockets comparten la misma URL pública.
 */
const http = require('http');
const net = require('net');

const WEB = { host: '127.0.0.1', port: 8000 };
const WS = { host: '127.0.0.1', port: 8080 };
const PROXY_PORT = 8088;

// HTTP -> Laravel
const server = http.createServer((req, res) => {
    const upstream = http.request(
        { ...WEB, path: req.url, method: req.method, headers: req.headers },
        (r) => {
            res.writeHead(r.statusCode, r.headers);
            r.pipe(res);
        }
    );
    upstream.on('error', () => {
        res.writeHead(502);
        res.end('Servidor web no disponible');
    });
    req.pipe(upstream);
});

// WebSocket -> Reverb (los mensajes en tiempo real)
server.on('upgrade', (req, socket, head) => {
    const upstream = net.connect(WS.port, WS.host, () => {
        let handshake = `${req.method} ${req.url} HTTP/1.1\r\n`;
        for (const [k, v] of Object.entries(req.headers)) {
            handshake += `${k}: ${Array.isArray(v) ? v.join(', ') : v}\r\n`;
        }
        handshake += '\r\n';
        upstream.write(handshake);
        if (head && head.length) upstream.write(head);
        socket.pipe(upstream);
        upstream.pipe(socket);
    });
    upstream.on('error', () => socket.destroy());
    socket.on('error', () => upstream.destroy());
});

server.listen(PROXY_PORT, '127.0.0.1', () => {
    console.log(`[proxy] http://127.0.0.1:${PROXY_PORT}  (web -> 8000 | websocket -> 8080)`);
});
